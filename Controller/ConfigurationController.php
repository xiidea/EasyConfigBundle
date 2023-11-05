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
    /**
     * @return Response
     */
    public function index(ConfigManager $manager): Response
    {
        $contents = [
            'contentHead' => 'Configurations ',
            'forms' => $manager->getConfigurationGroupForms(),
        ];

        return $this->render("@XiideaEasyConfig/index.html.twig", $contents);
    }

    /**
     * @return Response
     */
    public function list(ConfigManager $manager): Response
    {
        $contents = [
            'contentHead' => 'All Configuration ',
            'configurationGroup' => $manager->getConfigurationGroups(),
        ];

        return $this->render("@XiideaEasyConfig/list.html.twig", $contents);
    }

    /**
     * @param  string  $key
     * @return Response
     */
    public function form(string $key, ConfigManager $manager, FormFactoryInterface $formFactory): Response
    {
        $configurationGroup = $manager->getConfigurationGroup($key);
        $configurationGroupData = $manager->getConfigurationsByGroupKey($key);
        $form = $configurationGroup->getForm($formFactory, $configurationGroupData);

        return $this->render('@XiideaEasyConfig/index.html.twig', [
            'contentHead' => $configurationGroup->getLabel(),
            'forms' => [
                [
                    'key' => $key,
                    'label' => $configurationGroup->getLabel(),
                    'form' => $form->createView(),
                    'isEditable' => $this->isGranted($configurationGroup->getAuthorSecurityLevels()),
                ],
            ],
        ]);
    }

    /**
     * @param $key
     * @param  Request  $request
     * @return JsonResponse
     */
    public function save($key, Request $request, ConfigManager $manager): JsonResponse
    {
        $form = $manager->getConfigurationGroupForm($key);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            throw new FormValidationException($form);
        }

        if ($form->isValid()) {
            $manager->saveGroupData($key, $form);
        }

        return new JsonResponse([
            'success' => true,
            'message' => $manager->getConfigurationGroupLabel($key).' data updated!',
        ]);
    }

    /**
     * @param  string  $key
     * @return Response
     */
    public function userForm(string $key, ConfigManager $manager, FormFactoryInterface $formFactory): Response
    {
        $usernameKey = $manager->concatUsernameWithKey($key);
        $configurationGroup = $manager->getConfigurationGroup($usernameKey);
        $configurationGroupData = $manager->getUserConfigurationValuesByGroupKey($key);
        $form = $configurationGroup->getForm($formFactory, $configurationGroupData);

        return $this->render('@XiideaEasyConfig/user.html.twig', [
            'contentHead' => $configurationGroup->getLabel(),
            'forms' => [
                [
                    'key' => $usernameKey,
                    'label' => $configurationGroup->getLabel(),
                    'form' => $form->createView(),
                    'isEditable' => $this->isGranted($configurationGroup->getAuthorSecurityLevels()),
                ],
            ],
        ]);
    }

    /**
     * @param $key
     * @param  Request  $request
     * @return JsonResponse
     */
    public function saveUserConfig($key, Request $request, ConfigManager $manager): JsonResponse
    {
        $form = $manager->getConfigurationGroupForm($key);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            throw new FormValidationException($form);
        }

        if ($form->isValid()) {
            $manager->saveUserGroupData($key, $form);
        }

        return new JsonResponse([
            'success' => true,
            'message' => $manager->getConfigurationGroupLabel($key).' data updated!',
        ]);
    }

    public function getValueByKey(Request $request, ConfigManager $manager): JsonResponse
    {
        $result = $manager->getValueByKey($request->get('is_global'), $request->get('key'));

        return new JsonResponse($result);
    }
}
