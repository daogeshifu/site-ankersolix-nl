<?php

return [
    'title' => 'Foire aux questions',
    'subtitle' => 'Recherchez dans notre FAQ les réponses à toutes vos questions.',

    'sections' => [
        'detection' => [
            'title' => 'Détection IA',
            'questions' => [
                'what_is_ai_detection' => [
                    'question' => 'Qu\'est-ce que la détection de contenu IA ?',
                    'answer' => 'La détection de contenu IA est le processus d\'identification du texte généré par des outils d\'intelligence artificielle comme ChatGPT, GPT-4, Claude ou d\'autres modèles de langage. Nos algorithmes avancés analysent les modèles d\'écriture, les marqueurs linguistiques et les caractéristiques stylistiques pour déterminer si le contenu est généré par l\'IA ou écrit par un humain.'
                ],
                'how_accurate' => [
                    'question' => 'Quelle est la précision de votre détection IA ?',
                    'answer' => 'Notre système de détection IA maintient un taux de précision supérieur à 98% sur divers types de contenu. Nous utilisons des modèles d\'apprentissage automatique avancés formés sur des millions d\'échantillons pour identifier le contenu généré par l\'IA. Cependant, la précision peut varier selon la longueur, la qualité du contenu et le modèle d\'IA utilisé pour le générer.'
                ],
                'supported_languages' => [
                    'question' => 'Quelles langues le détecteur IA prend-il en charge ?',
                    'answer' => 'Notre détecteur IA prend actuellement en charge l\'anglais, l\'espagnol, le français, l\'allemand, le chinois, le japonais et de nombreuses autres langues principales. Nous élargissons continuellement la prise en charge linguistique pour servir un public mondial.'
                ],
                'detection_time' => [
                    'question' => 'Combien de temps faut-il pour détecter le contenu IA ?',
                    'answer' => 'La détection est presque instantanée. La plupart des analyses se terminent en 1 à 3 secondes, même pour les documents plus longs. Notre infrastructure avancée garantit un traitement rapide sans compromettre la précision.'
                ],
                'can_detect_all_ai' => [
                    'question' => 'Pouvez-vous détecter le contenu de tous les modèles d\'IA ?',
                    'answer' => 'Oui, notre système peut détecter le contenu généré par ChatGPT (GPT-3.5, GPT-4), Claude, Bard, Jasper, Copy.ai et la plupart des autres outils d\'écriture IA populaires. Nous mettons continuellement à jour nos algorithmes de détection pour identifier les nouveaux modèles d\'IA au fur et à mesure de leur apparition.'
                ],
                'false_positives' => [
                    'question' => 'Qu\'en est-il des faux positifs ?',
                    'answer' => 'Bien que nous nous efforcions d\'atteindre une précision maximale, des faux positifs peuvent parfois se produire, en particulier avec l\'écriture humaine hautement structurée ou formulaire. Nous fournissons des scores de confiance détaillés et des sections surlignées pour vous aider à prendre des décisions éclairées.'
                ]
            ]
        ],

        'humanization' => [
            'title' => 'Humanisation et Réécriture IA',
            'questions' => [
                'what_is_humanization' => [
                    'question' => 'Qu\'est-ce que l\'humanisation du contenu IA ?',
                    'answer' => 'L\'humanisation du contenu IA est le processus de transformation du texte généré par l\'IA pour le rendre plus naturel, authentique et humain. Notre outil réécrit le contenu tout en préservant le sens, en ajoutant des variations naturelles, des touches personnelles et des éléments stylistiques qui caractérisent l\'écriture humaine.'
                ],
                'how_does_rewriting_work' => [
                    'question' => 'Comment fonctionne le processus de réécriture IA ?',
                    'answer' => 'Notre moteur de réécriture analyse votre contenu et restructure les phrases, varie le vocabulaire, ajuste le ton et ajoute des imperfections naturelles qui rendent le texte apparemment écrit par un humain. Il maintient le message principal tout en rendant le contenu indétectable par les outils de détection IA.'
                ],
                'preserve_meaning' => [
                    'question' => 'La réécriture modifiera-t-elle le sens de mon contenu ?',
                    'answer' => 'Non. Nos algorithmes avancés sont conçus pour préserver le sens original, les faits clés et l\'intention de votre contenu. Nous ne modifions que le style d\'écriture, la structure des phrases et le choix des mots pour rendre le texte plus naturel et humain.'
                ],
                'quality_after_rewrite' => [
                    'question' => 'La qualité restera-t-elle élevée après la réécriture ?',
                    'answer' => 'Absolument. Notre processus d\'humanisation améliore en fait la qualité du contenu en le rendant plus engageant, naturel et lisible. Le contenu réécrit maintient la correction grammaticale tout en ajoutant des touches humaines qui améliorent la qualité globale.'
                ],
                'rewriting_time' => [
                    'question' => 'Combien de temps prend le processus de réécriture ?',
                    'answer' => 'La réécriture prend généralement 5 à 15 secondes selon la longueur du contenu. Les textes courts (moins de 500 mots) se traitent en environ 5 secondes, tandis que les documents plus longs peuvent prendre jusqu\'à 30 secondes.'
                ],
                'plagiarism_check' => [
                    'question' => 'Le contenu réécrit est-il exempt de plagiat ?',
                    'answer' => 'Oui. Notre processus de réécriture crée un contenu unique et complètement original. Le texte réécrit passera les vérifications de plagiat tout en conservant les idées et informations principales de l\'original.'
                ]
            ]
        ],

        'usage' => [
            'title' => 'Utilisation et Fonctionnalités',
            'questions' => [
                'word_limits' => [
                    'question' => 'Quelles sont les limites de mots pour chaque plan ?',
                    'answer' => 'Plan Essential : 150 000 mots/mois, Plan Professional : 500 000 mots/mois, Plan Premium : 300 000 mots/mois. Les mots non utilisés ne sont pas reportés au mois suivant.'
                ],
                'file_upload' => [
                    'question' => 'Puis-je télécharger des fichiers pour l\'analyse ?',
                    'answer' => 'Oui ! Les plans Professional et Premium prennent en charge le téléchargement de fichiers en lot. Vous pouvez télécharger des fichiers PDF, DOCX, TXT et d\'autres formats de documents courants. Le plan Essential prend uniquement en charge le collage de texte.'
                ],
                'batch_processing' => [
                    'question' => 'Puis-je analyser plusieurs documents à la fois ?',
                    'answer' => 'Le plan Professional permet d\'analyser jusqu\'à 250 fichiers simultanément. Le plan Premium prend en charge jusqu\'à 50 fichiers à la fois. Le plan Essential traite un document à la fois.'
                ],
                'api_access' => [
                    'question' => 'Offrez-vous un accès API ?',
                    'answer' => 'Oui, l\'accès API est disponible pour les plans Professional et Premium. Notre API RESTful permet une intégration transparente avec vos applications, CMS ou flux de travail. Nous fournissons une documentation complète et des exemples de code dans plusieurs langages.'
                ],
                'data_privacy' => [
                    'question' => 'Comment gérez-vous mes données et ma vie privée ?',
                    'answer' => 'Nous prenons la confidentialité des données au sérieux. Votre contenu est crypté pendant la transmission et le traitement. Nous ne stockons pas votre contenu de manière permanente et il est automatiquement supprimé après traitement. Nous ne partageons jamais vos données avec des tiers.'
                ],
                'reports_history' => [
                    'question' => 'Puis-je accéder à mon historique d\'analyse et aux rapports ?',
                    'answer' => 'Oui. Tous les plans incluent un historique d\'analyse avec des rapports téléchargeables. Vous pouvez exporter les résultats en fichiers PDF ou CSV. Les plans Professional et Premium conservent l\'historique pendant 12 mois ; le plan Essential pendant 3 mois.'
                ]
            ]
        ],

        'billing' => [
            'title' => 'Facturation et Paiements',
            'questions' => [
                'payment_methods' => [
                    'question' => 'Quels modes de paiement acceptez-vous ?',
                    'answer' => 'Nous acceptons toutes les principales cartes de crédit (Visa, MasterCard, American Express) via Stripe, PayPal et Alipay. Toutes les transactions sont sécurisées et cryptées.'
                ],
                'cancel_anytime' => [
                    'question' => 'Puis-je annuler mon abonnement à tout moment ?',
                    'answer' => 'Oui, vous pouvez annuler votre abonnement à tout moment. Il n\'y a pas de frais d\'annulation. Votre accès se poursuivra jusqu\'à la fin de votre période de facturation actuelle.'
                ],
                'refund_policy' => [
                    'question' => 'Quelle est votre politique de remboursement ?',
                    'answer' => 'Nous offrons une garantie de remboursement de 7 jours pour tous les plans. Si vous n\'êtes pas satisfait de notre service dans les 7 premiers jours, contactez-nous pour un remboursement complet, sans poser de questions.'
                ],
                'upgrade_downgrade' => [
                    'question' => 'Puis-je mettre à niveau ou rétrograder mon plan ?',
                    'answer' => 'Oui. Vous pouvez mettre à niveau ou rétrograder à tout moment. Lors de la mise à niveau, vous serez facturé d\'un montant au prorata. Lors de la rétrogradation, le changement prend effet lors de votre prochain cycle de facturation.'
                ],
                'annual_savings' => [
                    'question' => 'Combien puis-je économiser avec la facturation annuelle ?',
                    'answer' => 'Les plans annuels vous permettent d\'économiser jusqu\'à 45% par rapport à la facturation mensuelle. Par exemple, le plan Professional coûte 24,99$/mois facturé annuellement contre 45,99$/mois facturé mensuellement.'
                ],
                'invoice_receipt' => [
                    'question' => 'Vais-je recevoir des factures et des reçus ?',
                    'answer' => 'Oui. Vous recevrez un reçu par e-mail immédiatement après chaque paiement. Vous pouvez également télécharger des factures à tout moment depuis votre tableau de bord.'
                ]
            ]
        ],

        'support' => [
            'title' => 'Support et Aide',
            'questions' => [
                'customer_support' => [
                    'question' => 'Comment puis-je contacter le support client ?',
                    'answer' => 'Vous pouvez contacter notre équipe d\'assistance par e-mail à support@aigcchecker.com, via le chat en direct sur notre site Web (disponible 24h/24 et 7j/7 pour les utilisateurs Premium, pendant les heures de bureau pour les autres), ou en soumettant un ticket via votre tableau de bord.'
                ],
                'response_time' => [
                    'question' => 'Quel est le délai de réponse du support typique ?',
                    'answer' => 'Plan Essential : Dans les 48 heures. Plan Professional : Dans les 24 heures. Plan Premium : Support prioritaire avec réponse dans les 4 heures. Le chat en direct est disponible pour une assistance en temps réel.'
                ],
                'technical_issues' => [
                    'question' => 'Que se passe-t-il si je rencontre des problèmes techniques ?',
                    'answer' => 'Si vous rencontrez des problèmes techniques, veuillez contacter immédiatement notre équipe d\'assistance. Nous travaillerons à résoudre le problème aussi rapidement que possible et prolongerons votre abonnement si un temps d\'arrêt affecte votre service.'
                ],
                'training_resources' => [
                    'question' => 'Fournissez-vous une formation ou des tutoriels ?',
                    'answer' => 'Oui ! Nous proposons une documentation complète, des tutoriels vidéo et des webinaires. Les utilisateurs Professional et Premium ont également accès à des sessions d\'intégration personnalisées et à des supports de formation avancés.'
                ],
                'feature_requests' => [
                    'question' => 'Puis-je demander de nouvelles fonctionnalités ?',
                    'answer' => 'Absolument ! Nous écoutons activement nos utilisateurs. Soumettez des demandes de fonctionnalités via votre tableau de bord ou envoyez-nous un e-mail. Nous examinons régulièrement les suggestions et priorisons en fonction de la demande des utilisateurs.'
                ],
                'enterprise_support' => [
                    'question' => 'Offrez-vous un support entreprise ?',
                    'answer' => 'Oui. Les clients entreprise bénéficient de gestionnaires de compte dédiés, de SLA personnalisés, d\'un support prioritaire et de solutions sur mesure. Contactez notre équipe commerciale à enterprise@aigcchecker.com pour plus d\'informations.'
                ]
            ]
        ]
    ],

    'contact' => [
        'title' => 'Vous avez encore des questions ?',
        'description' => 'Vous ne trouvez pas la réponse que vous cherchez ? Veuillez discuter avec notre équipe amicale.',
        'button' => 'Nous contacter'
    ]
];
