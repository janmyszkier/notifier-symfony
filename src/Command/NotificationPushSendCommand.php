<?php

namespace App\Command;

use App\Model\NotificationRecipient;
use Symfony\Component\Console\Attribute\AsCommand;
use App\Service\NotificationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'notification:push:send')]
class NotificationPushSendCommand extends Command
{
    public function __construct(
        private NotificationService $notificationService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Send an Push Notification using the NotificationService.')
            ->addArgument('to', InputArgument::REQUIRED, 'The recipient device token.')
            ->addArgument('body', InputArgument::REQUIRED, 'The message.')
            ->addArgument('userId', InputArgument::REQUIRED, 'User id to track you by');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $to = $input->getArgument('to');
        $body = $input->getArgument('body');
        $userId = $input->getArgument('userId');

        $recipient = new NotificationRecipient(null,null,$to);

        try {
            $success = $this->notificationService->sendPushNotification($recipient, $body,$userId);
            if ($success) {
                $output->writeln("Push notification sent successfully.");
                return Command::SUCCESS;
            } else {
                $output->writeln("Push notification was not sent.");
                return Command::FAILURE;
            }

        } catch (\Exception $e) {
            $output->writeln("Failed to send Push Notification: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}