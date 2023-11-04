<?php

namespace Xiidea\EasyConfigBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Xiidea\EasyConfigBundle\Exception\FormValidationException;
use Xiidea\EasyConfigBundle\Services\Manager\ConfigManager;

class ConfigurationController extends AbstractController
{
    private ConfigManager $manager;
    private FormFactoryInterface $formFactory;

    public function __construct(ConfigManager $configManager, FormFactoryInterface $formFactory)
    {
        $this->manager = $configManager;
        $this->formFactory = $formFactory;
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        $contents = [
            'contentHead' => 'Configurations ',
            'forms' => $this->manager->getConfigurationGroupForms(),
        ];

        return $this->render("@XiideaEasyConfig/index.html.twig", $contents);
    }

    /**
     * @return Response
     */
    public function list(): Response
    {
        $contents = [
            'contentHead' => 'All Configuration ',
            'configurationGroup' => $this->manager->getConfigurationGroups(),
        ];

        return $this->render("@XiideaEasyConfig/list.html.twig", $contents);
    }

    /**
     * @param string $key
     * @return Response
     */
    public function form(string $key): Response
    {
        $configurationGroup = $this->manager->getConfigurationGroup($key);
        $configurationGroupData = $this->manager->getConfigurationsByGroupKey($key);
        $form = $configurationGroup->getForm($this->formFactory, $configurationGroupData);

        return $this->render('@XiideaEasyConfig/index.html.twig', [
            'contentHead' => $configurationGroup::getLabel(),
            'forms' => [
                [
                    'key' => $key,
                    'label' => $configurationGroup::getLabel(),
                    'form' => $form->createView(),
                    'isEditable' => $this->isGranted($configurationGroup::getAuthorSecurityLevels()),
                ]
            ]
        ]);
    }

    /**
     * @param $key
     * @param Request $request
     * @return JsonResponse
     */
    public function save($key, Request $request): JsonResponse
    {
        $form = $this->manager->getConfigurationGroupForm($key);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            throw new FormValidationException($form);
        }

        if ($form->isValid()) {
            $this->manager->saveGroupData($key, $form);
        }

        return new JsonResponse([
            'success' => true,
            'message' => $this->manager->getConfigurationGroupLabel($key) . ' data updated!'
        ]);
    }

    /**
     * @param string $key
     * @return Response
     */
    public function userForm(string $key): Response
    {
        $usernameKey = $this->manager->concatUsernameWithKey($key);
        $configurationGroup = $this->manager->getConfigurationGroup($usernameKey);
        $configurationGroupData = $this->manager->getUserConfigurationValuesByGroupKey($key);
        $form = $configurationGroup->getForm($this->formFactory, $configurationGroupData);

        return $this->render('@XiideaEasyConfig/user.html.twig', [
            'contentHead' => $configurationGroup::getLabel(),
            'forms' => [
                [
                    'key' => $usernameKey,
                    'label' => $configurationGroup::getLabel(),
                    'form' => $form->createView(),
                    'isEditable' => $this->isGranted($configurationGroup::getAuthorSecurityLevels()),
                ]
            ]
        ]);
    }

    /**
     * @param $key
     * @param Request $request
     * @return JsonResponse
     */
    public function saveUserConfig($key, Request $request): JsonResponse
    {
        $form = $this->manager->getConfigurationGroupForm($key);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            throw new FormValidationException($form);
        }

        if ($form->isValid()) {
            $this->manager->saveUserGroupData($key, $form);
        }

        return new JsonResponse([
            'success' => true,
            'message' => $this->manager->getConfigurationGroupLabel($key) . ' data updated!'
        ]);
    }

    public function getValueByKey(Request $request): JsonResponse
    {
        $result = $this->manager->getValueByKey($request->get('is_global'), $request->get('key'));

        return new JsonResponse($result);
    }
}
