<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'Ces identifiants ne correspondent pas à nos enregistrements.',
    'password' => 'Le mot de passe fourni est incorrect.',
    'throttle' => 'Trop de tentatives de connexion. Veuillez réessayer dans :seconds secondes.',

    'login' => [
        'title' => "Connexion",
        'management_slogan' => "Gérer efficacement vos paiements",
        'management_description' => "Connectez-vous pour accéder à votre tableau de bord et gérer vos services efficacement.",
        'login_heading' => "Connexion",
        'email_label' => "Adresse e-mail",
        'password_label' => "Mot de passe",
        'remember_me' => "Se souvenir de moi",
        'forgot_password' => "Mot de passe oublié ?",
        'login_button' => "Connexion",
        'no_account_yet' => "Pas encore de compte ?",
        'register_link' => "Inscrivez-vous",
    ],

    'confirm_password' => [
        'title' => "Confirmer le mot de passe",
        'secure_area_message' => "Ceci est une zone sécurisée de l'application. Veuillez confirmer votre mot de passe avant de continuer.",
        'password_label' => "Mot de passe",
        'confirm_button' => "Confirmer",
    ],

    'forgot_password' => [
        'title' => "Mot de passe oublié",
        'slogan' => "Un Oubli ?",
        'description' => "Pas de souci, nous vous aidons à retrouver l'accès à votre compte.",
        'heading' => "Mot de passe oublié",
        'instruction' => "Entrez votre e-mail et nous vous enverrons un lien pour réinitialiser votre mot de passe.",
        'email_label' => "Adresse e-mail",
        'send_link_button' => "Envoyer le lien",
    ],

    'register' => [
        'title' => "Inscription",
        'slogan' => "Rejoignez Notre Communauté",
        'description' => "Créez un compte pour commencer à gérer vos services de santé.",
        'heading' => "Créer un compte",
        'name_label' => "Nom complet",
        'email_label' => "Adresse e-mail",
        'password_label' => "Mot de passe",
        'password_confirmation_label' => "Confirmation du mot de passe",
        'register_button' => "S'inscrire",
        'already_registered' => "Déjà un compte ?",
        'login_link' => "Connectez-vous",
    ],

    'reset_password' => [
        'title' => "Réinitialiser le mot de passe",
        'slogan' => "Presque Terminé",
        'description' => "Choisissez un nouveau mot de passe sécurisé pour protéger votre compte.",
        'heading' => "Nouveau mot de passe",
        'email_label' => "Adresse e-mail",
        'password_label' => "Nouveau mot de passe",
        'password_confirmation_label' => "Confirmation du mot de passe",
        'reset_button' => "Réinitialiser",
    ],

    'two_factor_challenge' => [
        'title' => "Authentification à deux facteurs",
        'code_prompt' => "Veuillez confirmer l'accès à votre compte en saisissant le code d'authentification fourni par votre application d'authentification.",
        'recovery_code_prompt' => "Veuillez confirmer l'accès à votre compte en saisissant l'un de vos codes de récupération d'urgence.",
        'code_label' => "Code",
        'recovery_code_label' => "Code de récupération",
        'use_recovery_code_button' => "Utiliser un code de récupération",
        'use_authentication_code_button' => "Utiliser un code d'authentification",
        'login_button' => "Se connecter",
    ],

    'verify_email' => [
        'title' => "Vérification de l'e-mail",
        'verification_message' => "Merci pour votre inscription ! Avant de commencer, pourriez-vous vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer ? Si vous n'avez pas reçu l'e-mail, nous vous en enverrons volontiers un autre.",
        'verification_link_sent' => "Un nouveau lien de vérification a été envoyé à l'adresse e-mail que vous avez fournie lors de votre inscription.",
        'resend_button' => "Renvoyer l'e-mail de vérification",
        'logout_button' => "Se déconnecter",
    ],
];