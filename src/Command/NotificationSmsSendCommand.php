<?php

namespace App\Command;

use App\Model\NotificationRecipient;
use Symfony\Component\Console\Attribute\AsCommand;
use App\Service\NotificationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'notification:sms:send')]
class NotificationSmsSendCommand extends Command
{
    public function __construct(
        private NotificationService $notificationService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Send an SMS using the NotificationService.')
            ->addArgument('to', InputArgument::REQUIRED, 'The recipient international phone number.')
            ->addArgument('body', InputArgument::REQUIRED, 'The message.')
            ->addArgument('userId', InputArgument::REQUIRED, 'User id to track you by');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $to = $input->getArgument('to');
        $body = $input->getArgument('body');
        $userId = $input->getArgument('userId');

        $recipient = new NotificationRecipient(null,$to,null);

        try {
            $this->notificationService->sendSms($recipient, $body,$userId);
            $output->writeln("SMS sent successfully.");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("Failed to send SMS: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}