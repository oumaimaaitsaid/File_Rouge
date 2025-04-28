<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email? : Email de destination}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoyer un email de test pour vérifier la configuration';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Utiliser l'email fourni ou utiliser une valeur par défaut
        $email = $this->argument('email') ?? 'oumaimabellanova@gmail.com';
        
        $this->info("Envoi d'un email de test à {$email}...");
        
        try {
            Mail::raw('Ceci est un email de test pour vérifier la configuration mail de Laravel.', function($message) use ($email) {
                $message->to($email)
                        ->subject('Test de configuration email');
            });
            
            $this->info('Email envoyé avec succès!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}