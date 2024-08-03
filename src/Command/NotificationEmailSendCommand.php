<?php

namespace App\Command;

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
            ->addArgument('provider', InputArgument::REQUIRED, 'The email provider to use (e.g., aws_ses, smtp).')
            ->addArgument('to', InputArgument::REQUIRED, 'The recipient email address.')
            ->addArgument('subject', InputArgument::REQUIRED, 'The subject of the email.')
            ->addArgument('body', InputArgument::REQUIRED, 'The body of the email.')
            ->addArgument('userId', InputArgument::REQUIRED, 'User id to track you by');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $provider = $input->getArgument('provider');
        $to = $input->getArgument('to');
        $subject = $input->getArgument('subject');
        $body = $input->getArgument('body');
        $userId = $input->getArgument('userId');

        try {
            $this->notificationService->sendEmail($provider, $to, $subject, $body,$userId);
            $output->writeln("Email sent successfully using provider '$provider'.");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("Failed to send email: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}