<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\form;

use cooldogedev\BedrockEconomy\api\BedrockEconomyAPI;
use jojoe77777\FormAPI\CustomForm;
use pocketmine\player\Player;
use PromoSystem\Layton\PromoSystem;
use PromoSystem\Layton\response\Response;

class PromoActivateForm extends CustomForm {

    public function __construct(string $error = null) {
        $dataManager = PromoSystem::getInstance()->getDataManager();
        $queryHelper = PromoSystem::getInstance()->getTranslationManager()->getQueryHelper();

        parent::__construct(function (Player $player, array $data = null) use ($dataManager, $queryHelper) {
            if ($data === null) return;
            $promo = $data["promo"];

            if ($promo === "") {
                $player->sendForm(new PromoActivateForm("module.promo.form.input.empty"));
                return;
            }

            if (!$dataManager->isCreated($promo)) {
                $player->sendForm(new PromoActivateForm("module.promo.message.error.uncreated"));
                return;
            }

            if ($dataManager->isActivatedByUser($player, $promo)) {
                $player->sendForm(new PromoActivateForm("module.promo.message.error.activated"));
                return;
            }

            $amount = $dataManager->getAmount($promo);

            if ($dataManager->isUsesLimited($promo)) {
                $uses = $dataManager->getUses($promo);
                $maxUses = $dataManager->getMaxUses($promo);

                if ($uses >= $maxUses) {
                    $player->sendForm(new PromoActivateForm("module.promo.message.error.ended"));
                    return;
                }

                if ($dataManager->addToUser($player, $promo) instanceof Response) {
                    $player->sendForm(new PromoActivateForm("module.promo.message.error.cancelled"));
                    return;
                }

                $dataManager->setUses($promo, $uses + 1);
            } else {
                $creationTime = $dataManager->getCreationTime($promo);
                $actionTime = $dataManager->getActionTime($promo);

                if (time() >= ($creationTime + $actionTime)) {
                    $player->sendForm(new PromoActivateForm("module.promo.message.error.ended"));
                    return;
                }

                if ($dataManager->addToUser($player, $promo) instanceof Response) {
                    $player->sendForm(new PromoActivateForm("module.promo.message.error.cancelled"));
                    return;
                }

            }

            BedrockEconomyAPI::getInstance()->addToPlayerBalance($player->getName(), $amount);
            $player->sendMessage(str_replace("%amount%", (string) $amount, $queryHelper->getTranslatedString("module.promo.message.success")));
        });
        $this->setTitle($queryHelper->getTranslatedString("module.promo.form.title"));

        if ($error === null) {
            $this->addLabel($queryHelper->getTranslatedString("module.promo.form.label"));
        } else {
            $this->addLabel($queryHelper->getTranslatedString($error));
        }

        $this->addInput($queryHelper->getTranslatedString("module.promo.form.input.text"), $queryHelper->getTranslatedString("module.promo.form.input.placeholder"), "", "promo");
    }
    
}