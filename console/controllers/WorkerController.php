<?php
namespace console\controllers;

use udokmeci\yii2beanstalk\BeanstalkController;
use yii\helpers\Console;
use Yii;

class WorkerController extends BeanstalkController
{
    // Those are the default values you can override

    const DELAY_PRIORITY = "1000"; //Default priority
    const DELAY_TIME = 5; //Default delay time

    // Used for Decaying. When DELAY_MAX reached job is deleted or delayed with
    const DELAY_MAX = 3;

    public function listenTubes()
    {
        return [
            'tubeInvitEmail',
            'tubeForgetPwdEmail',
        ];
    }

    /**
    *
    * @param Pheanstalk\Job $job
    * @return string  self::BURY
    *                 self::RELEASE
    *                 self::DELAY
    *                 self::DELETE
    *                 self::NO_ACTION
    *                 self::DECAY
    *
    */
    public function actionTubeInvitEmail($job)
    {
        $sendData = $job->getData();

        try {
            $template = [
                'html' => $sendData->template->html,
                'text' => $sendData->template->text
            ];
            $everthingIsAllRight = \Yii::$app->mailer
                ->compose($template, [
                    'invitateCode' => $sendData->invitateCode,
                    'invitateUrl' => $sendData->invitateUrl,
                    'inviter' => $sendData->inviter
                ])
                ->setTo($sendData->sendTo)
                ->setSubject($sendData->subject)
                ->send();

            if ($everthingIsAllRight == true) {
                fwrite(STDOUT, Console::ansiFormat("- Everything is allright"."\n", [Console::FG_GREEN]));
                //Delete the job from beanstalkd
                return self::DELETE;
            }

            fwrite(STDOUT, Console::ansiFormat("- Not everything is allright!!!"."\n", [Console::FG_GREEN]));
            //Decay the job to try DELAY_MAX times.
            return self::DECAY;

            // if you return anything else job is burried.
        } catch (\Exception $e) {
            //If there is anything to do.
            fwrite(STDERR, Console::ansiFormat($e."\n", [Console::FG_RED]));
            // you can also bury jobs to examine later
            return self::BURY;
        }
    }

    public function actionTubeForgetPwdEmail($job)
    {
        $sendData = $job->getData();

        try {
            $template = [
                'html' => $sendData->template->html,
                'text' => $sendData->template->text
            ];
            $everthingIsAllRight = \Yii::$app->mailer
                ->compose($template, [
                    'nickname' => $sendData->nickname,
                    'resetLink' => $sendData->resetLink,
                ])
                ->setTo($sendData->sendTo)
                ->setSubject($sendData->subject)
                ->send();

            if ($everthingIsAllRight == true) {
                fwrite(STDOUT, Console::ansiFormat("- Everything is allright"."\n", [Console::FG_GREEN]));
                //Delete the job from beanstalkd
                return self::DELETE;
            }

            fwrite(STDOUT, Console::ansiFormat("- Not everything is allright!!!"."\n", [Console::FG_GREEN]));
            //Decay the job to try DELAY_MAX times.
            return self::DECAY;

            // if you return anything else job is burried.
        } catch (\Exception $e) {
            //If there is anything to do.
            fwrite(STDERR, Console::ansiFormat($e."\n", [Console::FG_RED]));
            // you can also bury jobs to examine later
            return self::BURY;
        }
    }
}
