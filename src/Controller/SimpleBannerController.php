<?php

declare(strict_types=1);

namespace cdigruttola\Module\SimpleBanner\Controller;

use cdigruttola\Module\SimpleBanner\Translations\TranslationDomains;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SimpleBannerController extends FrameworkBundleAdminController
{
    /**
     * @var array
     */
    private $languages;

    public function __construct($languages)
    {
        parent::__construct();
        $this->languages = $languages;
    }

    public function index(): Response
    {
        $configurationForm = $this->get('cdigruttola.module.simplebanner.configuration.form_handler')->getForm();

        return $this->render('@Modules/simplebanner/views/templates/admin/index.html.twig', [
            'translationDomain' => TranslationDomains::TRANSLATION_DOMAIN_ADMIN,
            'configurationForm' => $configurationForm->createView(),
            'help_link' => false,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function saveConfiguration(Request $request): Response
    {
        $redirectResponse = $this->redirectToRoute('simplebanner_controller');

        $form = $this->get('cdigruttola.module.simplebanner.configuration.form_handler')->getForm();
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return $redirectResponse;
        }

        if ($form->isValid()) {
            $data = $form->getData();
            $saveErrors = $this->get('cdigruttola.module.simplebanner.configuration.form_handler')->save($data);

            if (0 === count($saveErrors)) {
                $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));

                return $redirectResponse;
            }
        }

        $formErrors = [];

        foreach ($form->getErrors(true) as $error) {
            $formErrors[] = $error->getMessage();
        }

        $this->flashErrors($formErrors);

        return $redirectResponse;
    }

}
