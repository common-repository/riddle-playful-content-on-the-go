<?php

namespace src\classes\Landingpage;

class RiddleRandomLeadsGenerator
{

    private $leadFields;
    private $riddleId;
    private $title;

    private $leads;

    public function __construct(int $riddleId, $title, array $leadFields)
    {
        $this->leadFields = $leadFields;
        $this->riddleId = $riddleId;
        $this->title = $title;

        $this->leads = [];
    }

    public function getLeaderboardLeads() 
    {
        if ([] === $this->leads) {
            $this->prepareLeads(25);
        }

        $leaderboardLeads = [];
        $i = 1;

        foreach ($this->leads as $email => $lead) {
            $percentage = intval(rand(0, 100));
            $leaderboardLeads['entries'][] = [
                'key' => $email,
                'percentage' => $percentage,
                'placement' => $i,
                'createdAt' => (new \DateTime())->format('Y-m-d H:i:s'),
                'latestScoreNumber' => $percentage * 2,
                'sumScoreNumber' => $percentage * 2 * rand(1, 4),
                'sumScoreNumber' => 50,
                'trunk' => [
                    'timeTaken' => \rand(1, 10000),
                    'lead2' => $this->_getLeadArray($email),
                    'resultData' => $this->_getResultData($percentage),
                ],
            ];
            $leaderboardLeads['keyTable'][$email] = $i;
            $i++;
        }

        return $leaderboardLeads;
    }

    public function prepareLeads(int $count)
    {
        if ($count <= 0) {
            throw new InvalidArgumentException('The count must be greater than 0.');
        }

        for ($i = 0; $i < $count; $i++) {
            $email = $this->_getRandomEmail();
            $this->leads[$email] = $this->getLead($email, $i);
        }
    }

    public function getLeads()
    {
        return $this->leads;
    }

    public function getLeadAt($index)
    {
        $keys = array_keys($this->leads);
        
        return $this->leads[$keys[$index]];
    }

    public function getLead(string $email, $i)
    {
        return [
            'riddle' => $this->_getRiddleInfoArray(),
            'lead2' => $this->_getLeadArray($email),
            'resultData' => $this->_getResultData($i),
        ];
    }

    private function _getRiddleInfoArray()
    {
        return [
            'id' => $riddleId,
            'slug' => $slug,
        ];
    }

    private function _getLeadArray(string $email)
    {
        $leadValues = [];
        $leadFields = array_merge(['Email'], $this->leadFields);

        foreach ($leadFields as $leadField) {
            if ('Email' === $leadField) {
                $value = $email;
            } else if (in_array($leadField, ['index', 'percentage', 'time'])) {
                continue;
            } else {
                $value = 'Random_' . $leadField;
            }

            $leadValues[$leadField]['value'] = $value;
        }

        return $leadValues;
    }

    /**
     * Because we only render 3 leads that's okay
     */
    private function _getResultData(int $percentage)
    {
        return [
            'scorePercentage' => $percentage,
        ];
    }
    
    private function _getRandomEmail()
    {
        return mt_rand(0, 100000) . '-test@leaderboard.riddle';
    }

}