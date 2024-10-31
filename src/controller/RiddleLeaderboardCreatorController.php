<?php

namespace src\controller;

use src\Api\RiddleLoaderV2;
use src\classes\Landingpage\RiddleLeaderboardPreviewHandler;
use src\classes\Landingpage\Type\LeaderboardType;

class RiddleLeaderboardCreatorController extends RiddleLeaderboardBaseController
{
    public static function renderCreatorView(string $creatorPage)
    {
        $type = self::_getTypeFromSubpage($creatorPage);

        if (!$type) { // invalid subpage / type, redirect to index
            return self::redirectToAdminpage("riddle-admin-menu");
        }

        self::_insertParamsFromQuery($type);
        
        $leaderboardHandler = new RiddleLeaderboardPreviewHandler(); // to make sure that only the logged in user has access to the preview
        $leaderboardHandler->addRiddleId($type->getValue('id'));

        $params = [
            'availableFields' => self::_getAvailableFields($type),
            'type' => $type,
            'creatorPage' => $creatorPage,
            'creatorPages' => ['edit', 'leaderboard'],
            'riddle' => RiddleLoaderV2::getLoader()->getAPIClient()->riddleV1()->getRiddle($type->getValue('id')),
            'previewUrl' => RIDDLE_URL_PATH . '/public/riddle-leaderboard-preview.php',
        ];
        $pageDataMethod = '_getDataFor' . ucfirst($creatorPage);

        if (method_exists(__CLASS__, $pageDataMethod)) {
            $params = array_merge($params, self::$pageDataMethod($type));
        }

        return self::view('leaderboard-creator/_creator-template.php', $params);
    }

    private static function _getDataForEdit()
    {
        return [
            'previewUrl' => RIDDLE_URL_PATH . '/public/riddle-leaderboard-preview.php',
            'submitText' => 'SAVE CHANGES',
        ];
    }

    private static function _getDataForCreate()
    {
        $params = self::_getDataForEdit(); // needs the same data
        $params['submitText'] = 'CREATE';

        return $params;
    }

    private static function _getDataForLeads(LeaderboardType $type)
    {
        $storeService = $type->getLeaderboardHandler()->getApp()->getLeaderboardModule()->getStoreService();

        return [
            'leads' => $storeService->getLeaderboardLeads(),
        ];
    }

    private static function _getDataForLeaderboard()

    {
        return [
            'amountEntriesOptions' => [
                10 => '10',
                -1 => 'All',
                5 => 'Custom',
            ],
        ];
    }

    private static function _getAvailableFields(LeaderboardType $type)
    {
        $fieldsOrder = $type->getValue('leadfieldNamesOrder');
        $fieldsOrder = '' === $fieldsOrder ? [] : explode(',', $type->getValue('leadfieldNamesOrder')); // if previously saved - let's keep the order!

        $availableFields = [
            'index' => [
                'desc' => 'shows the ranking number, e.g. 1, 2, 3…',
            ],
            'percentage' => [
                'desc' => 'each user’s score, as %'
            ], 
            'latestScore' => [
                'desc' => 'the user\'s latest score e.g. 9/10 => 9',
            ], 
            'scoreSum' => [
                'desc' => 'the user\'s sum of all scores e.g. 9/10 & 8/10 => 9+8 = 17',
            ],
            'time' => [
                'desc' => 'How long it took each user to complete the quiz',
            ],
        ];
        $leadFields = RiddleLoaderV2::getLoader()->getAPIClient()->riddleV1()->getLeadFields($type->getValue('id'));
        $leadFields = array_merge($availableFields, $leadFields !== false ? $leadFields : []);

        $fields = [];

        foreach ($fieldsOrder as $fieldName) { // put the fields back into the used order
            if (isset($leadFields[$fieldName])) {
                $fields[$fieldName] = $leadFields[$fieldName];
                unset($leadFields[$fieldName]);
            }
        }

        return array_merge(
            $fields,
            $leadFields // add the fields that are not included in the order
        );
    }
}