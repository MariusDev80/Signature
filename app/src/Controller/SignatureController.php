<?php 

namespace Signature\Controller;

class SignatureController extends AbstractController
{
    public function create()
    {   
        $errorList = [];
        $user = $this->container->getCurrentUser();
        $entite = null;
        $banner = null;
        $base64 = null;
        $signatureData = [];
        $icons = [];
        $entiteList = $this->container->getEntiteRepository()->list();
        $bannerList = $this->container->getBannerRepository()->list();
        $defaultEntite = $this->container->getEntiteRepository()->identify("Makina Corpus");
 
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn'])) {

            $errorList = $this->validateValues($signatureData);

            $entite = $this
                ->container
                ->getEntiteRepository()
                ->find($_POST['entiteId'])
            ;
            $banner = $this 
                ->container
                ->getBannerRepository()
                ->find($_POST['bannerId'])
            ;

            $base64 = base64_encode(file_get_contents("banner/$banner->id.$banner->extension"));
            
            if ($entite->logoPath) {
                $icons['logo'] = base64_encode(file_get_contents("entite/logo/$entite->logoPath"));
            }

            if ($entite->logoFooterPath) {
                $icons['logoFooter'] = base64_encode(file_get_contents("entite/logoFooter/$entite->logoFooterPath"));
            }

            $signatureData['icons'] = $icons;
            $signatureData['entite'] = $entite;
            $signatureData['banner'] = $banner;
            $signatureData['base64'] = $base64;
            
            if ($errorList == []) {
                if (isset($_POST['numStandardCheck'])) {
                    $signatureData['numStandard'] = $this->numFormat($entite->numStandard);
                }

                if (isset($_POST['numProCheck']) && $user->numPro != null) {
                    $signatureData['numPro'] = $this->numFormat($user->numPro);
                }

                if (isset($_POST['defaultLinksCheck'])) {
                    $signatureData['footerLinks'] = $entite->getRsLinkList($defaultEntite);
                } else  {
                    $signatureData['footerLinks'] = $entite->getRsLinkList();
                }
            }

        } else {

            $entite = $this
            ->container
            ->getEntiteRepository()
            ->find($user->entite->id)
            ;
            $banner = $this 
                ->container
                ->getBannerRepository()
                ->find($user->entite->banniereRef->id)
            ;
            $base64 = base64_encode(file_get_contents("banner/$banner->id.$banner->extension"));



            if ($entite->logoPath) {
                $icons['logo'] = base64_encode(file_get_contents("entite/logo/$entite->logoPath"));
            }

            if ($entite->logoFooterPath) {
                $icons['logoFooter'] = base64_encode(file_get_contents("entite/logoFooter/$entite->logoFooterPath"));
            }

            $signatureData = [
                'footerLinks' => $entite->getRsLinkList(),
                'icons' => $icons,
                'entite' => $entite,
                'banner' => $banner,
                'base64' => $base64,
                'numStandard' => $this->numFormat($entite->numStandard),
                'numStandardCheck' => 1,
            ];
        }

        $this->container->getViewRenderer()->render(
            'signature/create.html.twig',
            [
                'errorList' => $errorList,
                'entiteList' => $entiteList,
                'bannerList' => $bannerList,
                'signatureData' => $signatureData
            ]
        );
    }

    private function numFormat(string $num): string
    {
        $num = str_split($num,2);
        $formatedNum = "+33 (0) "; 
        for ($seq=0;$seq<5;$seq++ ){
            if ($seq == 0) {
                $formatedNum.= substr($num[$seq],1);
            } else {
                $formatedNum.=  " ".$num[$seq]; 
            }
        }
        return $formatedNum;
    }

    private function validateValues(array &$signatureData)
    {   
        $errorList = [];

        $signatureData['poste'] = $_POST['poste'];
        if ($error = $this->validateField('poste', "#^[A-Za-z][A-Za-z- ]{0,40}$#", false)) {
            $errorList['poste'] = $error;
        }

        $signatureData['entiteId'] = $_POST['entiteId'];
        $signatureData['bannerId'] = $_POST['bannerId'];
        $signatureData['numStandardCheck'] = isset($_POST['numStandardCheck']);
        $signatureData['numProCheck'] = isset($_POST['numProCheck']);
        $signatureData['defaultLinksCheck'] = isset($_POST['defaultLinksCheck']);

        return $errorList;
    }
}
