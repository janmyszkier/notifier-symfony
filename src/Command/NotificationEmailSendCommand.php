<?php

namespace App\Command;

use App\Model\NotificationRecipient;
use Symfony\Component\Console\Attribute\AsCommand;
use App\Service\NotificationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'notification:email:send')]
class NotificationEmailSendCommand extends Command
{
    public function __construct(
        private NotificationService $notificationService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Send an email using the NotificationService.')
            ->addArgument('to', InputArgument::REQUIRED, 'The recipient email address.')
            ->addArgument('subject', InputArgument::REQUIRED, 'The subject of the email.')
            ->addArgument('body', InputArgument::REQUIRED, 'The body of the email.')
            ->addArgument('userId', InputArgument::REQUIRED, 'User id to track you by');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $to = $input->getArgument('to');
        $subject = $input->getArgument('subject');
        $body = $input->getArgument('body');
        $userId = $input->getArgument('userId');

        $recipient = new NotificationRecipient($to);

        try {
            $this->notificationService->sendEmail($recipient, $subject, $body,$userId);
            $output->writeln("Email sent successfully.");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("Failed to send email: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}