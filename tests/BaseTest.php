<?php

namespace Epíkoinos\Tests;

abstract class BaseTest extends \PHPUnit_Framework_TestCase
{

    protected static $skip = [
        'diplomé', 'diplomés', 'local', 'locaux', 'chef', 'chefs', 'chercheur',
        'ingénieur', 'entrepreneur', 'chercheurs', 'ingénieurs', 'entrepreneurs',
        'commis', 'sénior', 'séniors'
    ];

    public function sentenceProvider()
    {
        return [
            [
                "Devenez formateur, c'est bien d'être formateur.",
                "Devenez formateur.rice, c'est bien d'être formateur.rice.",
            ],
            [
                "Devenez formateur, c'est bien d'être formateur.rice",
                "Devenez formateur.rice, c'est bien d'être formateur.rice",
            ],
            [
                "Devenez formateur, c'est bien d'être formateur/tuteur",
                "Devenez formateur.rice, c'est bien d'être formateur.rice/tuteur.rice",
            ],
            [
                'teurgoule et tuteurat',
                'teurgoule et tuteurat',
            ],
            [
                'Étudiants et professeurs',
                'Étudiant.e.s et professeur.e.s',
            ],
            [
                "l'étudiant et le professeur",
                "l'étudiant.e et la.le professeur.e",
            ],
            [
                'le professeur, le professeur et le professeur',
                'la.le professeur.e, la.le professeur.e et la.le professeur.e',
            ],
            [
                "L'étudiant et le professeur",
                "L'étudiant.e et la.le professeur.e",
            ],
            [
                'un étudiant et un meuble',
                'un.e étudiant.e et un meuble',
            ],
            [
                'cet étudiant',
                'cet.te étudiant.e',
            ],
        ];
    }

    public function wordProvider()
    {
        return [
            ['le', 'la.le'],
            ['un', 'un.e'],
            ['ce', 'ce.tte'],
            //
            ['les', 'les'],
            ['des', 'des'],
            ['ces', 'ces'],
            ['ceux', 'ceux.elles'],

            ['tout', 'tout.e'],
            //
            ['tous', 'tou.te.s'],

            ['artiste', 'artiste'],
            ['bénévole', 'bénévole'],
            ['cadre', 'cadre'],
            ['capitaine', 'capitaine'],
            ['diplomate', 'diplomate'],
            ['fonctionnaire', 'fonctionnaire'],
            ['gendarme', 'gendarme'],
            ['guide', 'guide'],
            ['interprète', 'interprète'],
            ['juriste', 'juriste'],
            ['maire', 'maire'],
            ['membre', 'membre'],
            ['propriétaire', 'propriétaire'],
            ['secrétaire', 'secrétaire'],
            //
            ['artistes', 'artistes'],
            ['bénévoles', 'bénévoles'],
            ['cadres', 'cadres'],
            ['capitaines', 'capitaines'],
            ['diplomates', 'diplomates'],
            ['fonctionnaires', 'fonctionnaires'],
            ['gendarmes', 'gendarmes'],
            ['guides', 'guides'],
            ['interprètes', 'interprètes'],
            ['juristes', 'juristes'],
            ['maires', 'maires'],
            ['membres', 'membres'],
            ['propriétaires', 'propriétaires'],
            ['secrétaires', 'secrétaires'],

            ['administré', 'administré.e'],
            ['apprenti', 'apprenti.e'],
            ['attaché', 'attaché.e'],
            ['chargé', 'chargé.e'],
            ['délégué', 'délégué.e'],
            ['député', 'député.e'],
            array('diplomé', 'diplomé.e'),
            ['retraité', 'retraité.e'],
            //
            ['administrés', 'administré.e.s'],
            ['apprentis', 'apprenti.e.s'],
            ['attachés', 'attaché.e.s'],
            ['chargés', 'chargé.e.s'],
            ['délégués', 'délégué.e.s'],
            ['députés', 'député.e.s'],
            array('diplomés', 'diplomé.e.s'),
            ['retraités', 'retraité.e.s'],

            ['élu', 'élu.e'],
            //
            ['élus', 'élu.e.s'],

            ['départemental', 'départemental.e'],
            array('local', 'local.e'),
            ['médical', 'médical.e'],
            ['municipal', 'municipal.e'],
            ['préfectoral', 'préfectoral.e'],
            ['régional', 'régional.e'],
            ['social', 'social.e'],
            ['syndical', 'syndical.e'],
            ['territorial', 'territorial.e'],
            //
            ['départementaux', 'départementaux.ales'],
            array('locaux', 'locaux.ales'),
            ['médicaux', 'médicaux.ales'],
            ['municipaux', 'municipaux.ales'],
            ['préfectoraux', 'préfectoraux.ales'],
            ['régionaux', 'régionaux.ales'],
            ['sociaux', 'sociaux.ales'],
            ['syndicaux', 'syndicaux.ales'],
            ['territoriaux', 'territoriaux.ales'],

            array('chef', 'chef.fe'),
            //
            array('chefs', 'chef.fe.s'),

            ['intellectuel', 'intellectuel.le'],
            ['maternel', 'maternel.le'],
            ['professionnel', 'professionnel.le'],
            //
            ['intellectuels', 'intellectuel.le.s'],
            ['maternels', 'maternel.le.s'],
            ['professionnels', 'professionnel.le.s'],

            ['citoyen', 'citoyen.ne'],
            ['gardien', 'gardien.ne'],
            ['lycéen', 'lycéen.ne'],
            ['technicien', 'technicien.ne'],
            //
            ['citoyens', 'citoyen.ne.s'],
            ['gardiens', 'gardien.ne.s'],
            ['lycéens', 'lycéen.ne.s'],
            ['techniciens', 'technicien.ne.s'],

            ['banquier', 'banquier.ère'],
            ['conseiller', 'conseiller.ère'],
            ['écolier', 'écolier.ère'],
            ['hospitalier', 'hospitalier.ère'],
            ['officier', 'officier.ère'],
            ['premier', 'premier.ère'],
            ['policier', 'policier.ère'],
            ['usager', 'usager.ère'],
            //
            ['banquiers', 'banquier.ère.s'],
            ['conseillers', 'conseiller.ère.s'],
            ['écoliers', 'écolier.ère.s'],
            ['hospitaliers', 'hospitalier.ère.s'],
            ['officiers', 'officier.ère.s'],
            ['premiers', 'premier.ère.s'],
            ['policiers', 'policier.ère.s'],
            ['usagers', 'usager.ère.s'],

            ['chroniqueur', 'chroniqueur.euse'],
            ['entraineur', 'entraineur.euse'],
            ['footballeur', 'footballeur.euse'],
            ['programmeur', 'programmeur.euse'],
            ['travailleur', 'travailleur.euse'],
            //
            ['chroniqueurs', 'chroniqueur.euse.s'],
            ['entraineurs', 'entraineur.euse.s'],
            ['footballeurs', 'footballeur.euse.s'],
            ['programmeurs', 'programmeur.euse.s'],
            ['travailleurs', 'travailleur.euse.s'],

            array('chercheur', 'chercheur.e'),
            ['gouverneur', 'gouverneur.e'],
            array('ingénieur', 'ingénieur.e'),
            array('entrepreneur', 'entrepreneur.e'),
            ['professeur', 'professeur.e'],
            //
            array('chercheurs', 'chercheur.e.s'),
            ['gouverneurs', 'gouverneur.e.s'],
            array('ingénieurs', 'ingénieur.e.s'),
            array('entrepreneurs', 'entrepreneur.e.s'),
            ['professeurs', 'professeur.e.s'],

            ['ambassadeur', 'ambassadeur.rice'],
            //
            ['ambassadeurs', 'ambassadeur.rice.s'],

            ['acheteur', 'acheteur.euse'],
            ['transporteur', 'transporteur.euse'],
            //
            ['acheteurs', 'acheteur.euse.s'],
            ['transporteurs', 'transporteur.euse.s'],

            ['acteur', 'acteur.rice'],
            ['administrateur', 'administrateur.rice'],
            ['animateur', 'animateur.rice'],
            ['agriculteur', 'agriculteur.rice'],
            ['consommateur', 'consommateur.rice'],
            ['directeur', 'directeur.rice'],
            ['éducateur', 'éducateur.rice'],
            ['électeur', 'électeur.rice'],
            ['instituteur', 'instituteur.rice'],
            ['inspecteur', 'inspecteur.rice'],
            ['médiateur', 'médiateur.rice'],
            ['modérateur', 'modérateur.rice'],
            ['sénateur', 'sénateur.rice'],
            ['recteur', 'recteur.rice'],
            //
            ['acteurs', 'acteur.rice.s'],
            ['administrateurs', 'administrateur.rice.s'],
            ['animateurs', 'animateur.rice.s'],
            ['agriculteurs', 'agriculteur.rice.s'],
            ['consommateurs', 'consommateur.rice.s'],
            ['directeurs', 'directeur.rice.s'],
            ['éducateurs', 'éducateur.rice.s'],
            ['électeurs', 'électeur.rice.s'],
            ['instituteurs', 'instituteur.rice.s'],
            ['inspecteurs', 'inspecteur.rice.s'],
            ['médiateurs', 'médiateur.rice.s'],
            ['modérateurs', 'modérateur.rice.s'],
            ['sénateurs', 'sénateur.rice.s'],
            ['recteurs', 'recteur.rice.s'],

            ['auteur', 'auteur.e'],
            ['auteurs', 'auteur.e.s'],

            ['administratif', 'administratif.ve'],
            ['créatif', 'créatif.ve'],
            ['sportif', 'sportif.ve'],
            //
            ['administratifs', 'administratif.ve.s'],
            ['créatifs', 'créatif.ve.s'],
            ['sportifs', 'sportif.ve.s'],

            ['adhérent', 'adhérent.e'],
            ['adjoint', 'adjoint.e'],
            ['agent', 'agent.e'],
            ['avocat', 'avocat.e'],
            ['assistant', 'assistant.e'],
            ['consultant', 'consultant.e'],
            ['étudiant', 'étudiant.e'],
            ['habitant', 'habitant.e'],
            ['président', 'président.e'],
            ['remplaçant', 'remplaçant.e'],
            ['suppléant', 'suppléant.e'],
            //
            ['adhérents', 'adhérent.e.s'],
            ['adjoints', 'adjoint.e.s'],
            ['agents', 'agent.e.s'],
            ['avocats', 'avocat.e.s'],
            ['assistants', 'assistant.e.s'],
            ['consultants', 'consultant.e.s'],
            ['étudiants', 'étudiant.e.s'],
            ['habitants', 'habitant.e.s'],
            ['présidents', 'président.e.s'],
            ['remplaçants', 'remplaçant.e.s'],
            ['suppléants', 'suppléant.e.s'],

            ['artisan', 'artisan.e'],
            array('commis', 'commis.e'),
            array('sénior', 'sénior.e'),
            //
            ['artisans', 'artisan.e.s'],
            array('commis', 'commis.e.s'),
            array('séniors', 'sénior.e.s'),
            ['nombreux', 'nombreux.ses'],
        ];
    }

    public function wordProviderError()
    {
        return [
            ['foobar', 'foobar'],
            ['celui.elle', 'celui.elle'],
        ];
    }
}
