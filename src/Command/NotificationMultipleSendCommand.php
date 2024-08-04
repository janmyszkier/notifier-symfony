<?php

namespace App\Command;

use App\Model\NotificationRecipient;
use Symfony\Component\Console\Attribute\AsCommand;
use App\Service\NotificationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'notification:multiple:send', description: 'Send notification through multiple channels')]
class NotificationMultipleSendCommand extends Command
{
    public function __construct(
        private NotificationService $notificationService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Send a notification using multiple channels [sms|email|push_notification] using the NotificationService.')
            ->addOption('body', 'b',InputOption::VALUE_REQUIRED, 'The message to be sent.')
            ->addOption('userId','u', InputOption::VALUE_REQUIRED, 'User id to track you by')
            ->addOption('to_phone','p', InputOption::VALUE_OPTIONAL, 'The recipient international phone number.')
            ->addOption('to_address', 'a',InputOption::VALUE_OPTIONAL, 'The recipient email address.')
            ->addOption('subject', 's',InputOption::VALUE_OPTIONAL, 'The subject of the email.')
            ->addOption('to_device', 'd', InputOption::VALUE_OPTIONAL, 'The recipient device identifier.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $toEmailAddress = $input->getOption('to_address');
        $subject = $input->getOption('subject');

        $toPhoneNumber = $input->getOption('to_phone');
        $toDeviceIdentifier = $input->getOption('to_device');

        $body = $input->getOption('body');
        $userId = $input->getOption('userId');

        $recipient = new NotificationRecipient($toEmailAddress,$toPhoneNumber,$toDeviceIdentifier);

        $channels = [];
        if($toEmailAddress && $subject){
            $channels[] = 'email';
        }
        if($toPhoneNumber){
            $channels[] = 'sms';
        }
        if($toDeviceIdentifier){
            $channels[] = 'push_notification';
        }

        if(empty($channels)){
            $output->writeln('No channels configured, skipping');
            return Command::FAILURE;
        }

        try {
            $this->notificationService->sendNotification($recipient, $subject, $body,$userId, $channels);
            $output->writeln("Notifications sent successfully.");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("Failed to send email: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}