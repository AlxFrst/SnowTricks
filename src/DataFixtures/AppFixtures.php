<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Pictures;
use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Post;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    public const ADMIN_USER_REFERENCE = 'admin';
    public const USER_USER_REFERENCE = 'user';


    private static array $categoryNameArray = [
        // Regroupement par type de tricks
        "One foot tricks", // Les tricks spécifiques en premier
        "Front flips", // Les flips
        "Back flips",
        "Rotation", // Rotation comme une catégorie générale pour comprendre les valeurs suivantes
        "180", // Les rotations en ordre croissant
        "270",
        "360",
        "450",
        "540",
        "630",
        "720",
        "810",
        "900",
        "1080",
        // Grabs
        "Nose grab", // Les grabs
        "Japan",
        "Seat belt",
        "Truck driver",
        // Slides
        "Slide", // Les slides en général
        "Nose slide",
        "Tail slide"
    ];

    private static array $categorySlugArray = [
        // Regroupement par type de tricks
        "One foot tricks", // Les tricks spécifiques en premier
        "Front flips", // Les flips
        "Back flips",
        "Rotation", // Rotation comme une catégorie générale pour comprendre les valeurs suivantes
        "180", // Les rotations en ordre croissant
        "270",
        "360",
        "450",
        "540",
        "630",
        "720",
        "810",
        "900",
        "1080",
        // Grabs
        "Nose grab", // Les grabs
        "Japan",
        "Seat belt",
        "Truck driver",
        // Slides
        "Slide", // Les slides en général
        "Nose slide",
        "Tail slide"
    ];

    private static array $trickNameArray = [
        // Tricks de base
        "Ollie",
        "Nollie",
        "Butter",
        "Tripod",

        // Grabs
        "Melon",
        "Indy",
        "Nose Grab",
        "Tail Grab",

        // Rotations et Flips
        "Frontside 180",
        "Backside 180",
        "Frontside 360",
        "Backside 360",
        "Frontside 360+", // Supposons que cela signifie une rotation de plus de 360 degrés
        "Backside 360+",  // Supposons que cela signifie une rotation de plus de 360 degrés
        "Back Flip",
        "Front Flip",
        "Front Roll", // Bien que moins commun, inclus dans les rotations/flips

        // Slides et Presses
        "50-50",
        "Boardslide",
        "Tail Press",
        "Nose Press",

        // Ajouts supplémentaires pour enrichir la liste
        "540", // Ajout d'une rotation supplémentaire
        "720", // Ajout d'une rotation supplémentaire
        "McTwist", // Un flip aérien combiné à une rotation, très populaire en halfpipe
        "Rail Jam", // Trick spécifique aux rails
        "Switch", // Technique de changement de position
        "Half-Cab", // Un nollie frontside 180
        "Full-Cab", // Un nollie frontside 360
        "Caballerial", // Un fakie backside 360
        "Method Air" // Un grab spécifique en snowboard très iconique
    ];

    private static array $trickSlugArray = [
        // Tricks de base
        "Ollie",
        "Nollie",
        "Butter",
        "Tripod",

        // Grabs
        "Melon",
        "Indy",
        "Nose Grab",
        "Tail Grab",

        // Rotations et Flips
        "Frontside 180",
        "Backside 180",
        "Frontside 360",
        "Backside 360",
        "Frontside 360+", // Supposons que cela signifie une rotation de plus de 360 degrés
        "Backside 360+",  // Supposons que cela signifie une rotation de plus de 360 degrés
        "Back Flip",
        "Front Flip",
        "Front Roll", // Bien que moins commun, inclus dans les rotations/flips

        // Slides et Presses
        "50-50",
        "Boardslide",
        "Tail Press",
        "Nose Press",

        // Ajouts supplémentaires pour enrichir la liste
        "540", // Ajout d'une rotation supplémentaire
        "720", // Ajout d'une rotation supplémentaire
        "McTwist", // Un flip aérien combiné à une rotation, très populaire en halfpipe
        "Rail Jam", // Trick spécifique aux rails
        "Switch", // Technique de changement de position
        "Half-Cab", // Un nollie frontside 180
        "Full-Cab", // Un nollie frontside 360
        "Caballerial", // Un fakie backside 360
        "Method Air" // Un grab spécifique en snowboard très iconique
    ];

    private static array $trickDescriptionArray = [
        "L'Ollie est la base du snowboard freestyle. Déplacez votre poids sur votre jambe arrière, puis sautez, en commençant le mouvement avec votre jambe avant. Soulevez votre jambe arrière pour l'aligner avec l'avant.",
        "Le Nollie, l'inverse de l'Ollie, commence par un saut de la jambe arrière. Soulevez ensuite votre jambe avant pour équilibrer vos pieds, gagnant de l'altitude à chaque essai.",
        "Pour un Melon, saisissez le côté talon de votre planche entre vos pieds en l'air. C'est votre introduction aux grabs.",
        "L'Indy s'exécute en attrapant le bord des orteils de votre planche après un Ollie. Relâchez pour atterrir en douceur.",
        "Le Nose Grab implique de se pencher en l'air pour saisir l'avant de la planche, préparant à un atterrissage fluide.",
        "Le 50-50 est un trick de slide de base sur un rail ou une boîte, en glissant du début à la fin.",
        "Pour un Tail Press, penchez-vous en arrière en déplaçant votre poids sur votre jambe arrière, accentuant la courbure de la planche.",
        "Le Nose Press se réalise en se penchant en avant, en déplaçant le poids sur la jambe avant pour presser le nose de la planche.",
        "Le Frontside 180 fait tourner votre corps de sorte que vos talons mènent, changeant votre jambe de tête.",
        "Le Backside 180 est l'opposé, avec une rotation où vos orteils mènent.",
        "Le Butter implique de faire pivoter votre planche avec une jambe en avant tout en maintenant le contact avec la neige.",
        "Un Tripod se fait en soulevant une extrémité de la planche et en se penchant pour toucher le sol avec les mains.",
        "Pour un Tail Grab, saisissez la queue de votre snowboard en l'air.",
        "Le Boardslide fait glisser votre planche perpendiculairement sur un rail.",
        "Le Back Flip nécessite un saut puissant pour un flip complet avant l'atterrissage.",
        "Un Frontside 360 est une rotation complète vers l'avant avec vos talons menant.",
        "Un Backside 360 est une rotation complète avec vos orteils menant.",
        "Le Front Roll est un mouvement de rotation vers l'avant avec une légère inclinaison.",
        "Le Front Flip est un flip avant nécessitant un mouvement de corps recroquevillé pour réussir.",
        "Le Frontside 360+ est une rotation frontale de plus de 360 degrés.",
        "Le Backside 360+ est l'opposé, avec une rotation arrière de plus de 360 degrés.",
        "Le 540 est une rotation aérienne de un et demi tour, un défi d'équilibre et de précision.",
        "Le 720, ou deux tours complets, demande une maîtrise aérienne et un timing impeccable.",
        "Le McTwist est un flip aérien combiné à une rotation, une figure spectaculaire en half-pipe.",
        "Le Rail Jam est un trick avancé sur rail, exigeant un contrôle et une créativité exceptionnels.",
        "Le Switch fait référence à rider en position inversée, ajoutant un défi supplémentaire à tout trick.",
        "Le Half-Cab est un nollie frontside 180, parfait pour ajouter une touche de style.",
        "Le Full-Cab, ou nollie frontside 360, combine rotation et élévation pour un effet spectaculaire.",
        "Le Caballerial est un fakie backside 360, un trick de rotation arrière exécuté à partir d'une position fakie.",
        "Le Method Air se distingue par un grab spécifique où le rider saisit le bord de sa planche tout en étendant les jambes vers l'arrière, offrant une posture aérienne emblématique."
    ];


    private static array $PictureNameList = [
        'figure1.jpg',
        'figure2.jpg',
        'figure3.jpg',
        'figure4.jpg',
        'figure5.jpg',
        'figure6.jpg',
        'figure7.jpg'
    ];

    private static array $videoLinkList = [
        "mBB7CznvSPQ",
        "SFYYzy0UF-8",
        "PxhfDec8Ays",
        "LyfFuv4_wjQ",
        "oI-umOzNBME",
        "L4bIunv8fHM"
    ];

    private static array $commentList = [
        "Quelle maîtrise incroyable, tu fais ça depuis combien de temps ?",
        "Je suis toujours impressionné par la fluidité de tes mouvements. Des conseils pour les débutants ?",
        "Spectaculaire! J'aimerais voir plus de vidéos comme celle-ci.",
        "C'est le genre de performance qui m'inspire à sortir et à pratiquer davantage. Merci!",
        "La technique est impeccable, tu as des conseils pour améliorer l'équilibre ?",
        "J'ai essayé cela le week-end dernier et c'était bien plus difficile que prévu. Comment restes-tu si concentré ?",
        "Ton engagement et ta passion sont évidents dans chaque mouvement. Bravo!",
        "Je suis bluffé par la précision de tes tricks. As-tu un entraîneur ou est-ce tout en autodidacte ?",
        "Ça doit prendre des années pour atteindre ce niveau. Quel est le trick le plus difficile que tu aies maîtrisé ?",
        "Quels exercices recommandes-tu pour renforcer les muscles nécessaires à ces tricks ?",
        "La façon dont tu as atterri ce trick était tout simplement élégante. Peux-tu partager des astuces sur l'atterrissage ?",
        "Je travaille sur le même trick, mais j'ai du mal avec la rotation. Des suggestions ?",
        "Ta progression est vraiment inspirante! Combien de temps pratiques-tu chaque jour ?",
        "Impressionnant! Quelle est la prochaine étape pour toi ? Un trick encore plus audacieux ?",
        "La confiance que tu dégages en exécutant ces tricks est contagieuse. Je me sens motivé à essayer moi-même.",
        "Comment gères-tu la peur de l'échec ou de la chute lors de l'apprentissage de nouveaux tricks ?",
        "Ce trick spécifique a l'air si fluide quand tu le fais. Peux-tu expliquer un peu le processus derrière ?",
        "Vraiment cool de voir ta progression et comment tu repousses tes limites. Quel est ton secret ?",
        "Tu rends cela tellement facile! J'ai tenté plusieurs fois mais pas encore là. Des exercices préparatoires à recommander ?",
        "Quel équipement utilises-tu pour te préparer à ces tricks avancés ?",
        "Impressionnant ! La façon dont tu as exécuté ce 720 était parfaite. As-tu des astuces pour garder un tel contrôle en l'air ?",
        "Chapeau pour cette performance. Le backflip avait l'air si fluide. Ça fait combien de temps que tu t'entraînes ?",
        "Cet Indy grab était juste sublime. Tu captures tellement bien l'essence du mouvement !",
        "Wow, je suis épaté par ta technique de frontside 360. Peux-tu partager comment tu t'entraînes ?",
        "Quelle inspiration ! Voir tes tricks me donne envie de redoubler d'effort pour améliorer mes propres figures.",
        "J'ai tenté le nose grab après avoir vu ta vidéo. Des conseils pour ceux d'entre nous qui luttent pour maintenir l'équilibre ?",
        "Le tail press était si stylé ! Comment fais-tu pour rester aussi stable ?",
        "Je suis bluffé par ta capacité à enchaîner les tricks avec tant de fluidité. Quel est ton secret ?",
        "Le butter que tu as réalisé était incroyable. J'aimerais savoir comment tu maintiens une telle fluidité tout au long.",
        "Ton tripod m'a vraiment impressionné. Comment as-tu appris à le maîtriser à ce point ?",
        "Je travaille sur le boardslide, mais j'ai du mal avec l'atterrissage. Des suggestions ?",
        "La précision de tes rotations est remarquable. Utilises-tu des exercices spécifiques pour améliorer ta technique ?",
        "Ton front flip m'a laissé sans voix. C'est le prochain trick sur ma liste à apprendre !",
        "Je suis nouveau dans le snowboard. Voir tes performances me motive énormément. Quels conseils donnerais-tu à un débutant ?",
        "L'élégance et la confiance que tu dégages en réalisant chaque trick sont vraiment inspirantes.",
        "Le front roll semblait si aisé quand tu l'as fait. Pourrais-tu expliquer un peu plus comment aborder ce trick ?",
        "Je me demandais si tu pouvais partager des astuces sur la façon de mieux atterrir après un saut élevé.",
        "Chaque fois que je vois tes tricks, je suis émerveillé par ta créativité. Comment trouves-tu l'inspiration pour de nouvelles figures ?",
        "Le McTwist était absolument époustouflant. Cela a dû demander des années de pratique !",
        "C'est incroyable comment tu rends le half-cab look si simple. J'aimerais voir un tutoriel sur celui-ci.",
        "Comment restes-tu si concentré pendant des tricks compliqués comme le 720 ou le McTwist ?",
        "Le niveau de détail et de précision dans chaque trick est tellement professionnel. As-tu des routines d'entraînement spéciales ?",
        "Voir tes figures me pousse à sortir et pratiquer, même les jours où je ne le sens pas. Merci pour l'inspiration !",
        "As-tu des recommandations de spots pour pratiquer des tricks avancés en toute sécurité ?",
        "Le switch que tu as réalisé était si fluide. Peux-tu parler de la transition entre les positions ?",
        "J'adore comment tu expliques les aspects techniques de chaque trick. Ça aide vraiment les novices comme moi.",
        "La façon dont tu as intégré le rail jam dans ta routine était innovante. As-tu des conseils pour ceux qui veulent l'essayer ?",
        "C'est génial de voir quelqu'un maîtriser autant de tricks différents. Ça montre la diversité et la beauté du snowboard.",
        "Ton backside 360+ était juste parfait. Comment parviens-tu à maintenir un tel équilibre ?",
        "Le full-cab m'a totalement impressionné. C'est le genre de trick que je rêve de réussir un jour.",
        "Le caballerial était d'une beauté à couper le souffle. C'est impressionnant de voir autant de maîtrise et de technique.",
        "Je suis toujours à la recherche de nouvelles figures à ajouter à mon répertoire. Tes performances sont une mine d'or d'inspiration.",
        "La communauté a tellement à apprendre de riders comme toi. Merci de partager tes connaissances et ton expérience.",
        "Chaque trick que tu réalises avec tant d'aisance me rappelle pourquoi j'aime ce sport. Continue de nous inspirer !",
        "Ton engagement envers le snowboard et le perfectionnement de chaque trick est évident. C'est très motivant.",
        "La méthode air que tu as exécutée était d'une pureté incroyable. Peux-tu partager des conseils pour ceux d'entre nous qui cherchent à l'améliorer ?",
        "La progression de tes tricks est remarquable. Peux-tu partager ton parcours d'apprentissage pour aider les autres ?",
        "Voir quelqu'un d'aussi passionné par le snowboard est rafraîchissant. Cela me rappelle pourquoi je suis tombé amoureux de ce sport.",
        "Le niveau de dévouement nécessaire pour réaliser des tricks comme les tiens est incroyable. Bravo pour tout ce travail acharné.",
        "Tes vidéos sont toujours un régal à regarder. Elles sont pleines d'action, de conseils utiles, et d'inspiration.",
        "La façon dont tu décomposes les étapes pour chaque trick est très utile. Cela aide vraiment à comprendre le processus.",
        "Je suis impressionné par ta polyvalence. Du half-pipe au freestyle, tu sembles maîtriser tous les aspects du snowboard.",
        "La créativité et l'originalité de tes tricks sont vraiment uniques. Cela montre une profonde compréhension du snowboard.",
        "J'ai remarqué une amélioration significative dans mes propres performances en suivant tes conseils. Merci énormément !",
        "La passion que tu mets dans chaque vidéo et chaque trick est contagieuse. Cela me donne envie de m'améliorer.",
        "Je suis étonné par la facilité avec laquelle tu réalises des tricks qui me semblent impossibles.",
        "Ton approche pédagogique pour enseigner des tricks complexes est vraiment appréciée. Ça aide beaucoup les débutants.",
        "La confiance et le style que tu affiches dans chaque trick sont aspirants. Ça donne envie de repousser ses limites.",
        "Merci pour tous les tutoriels et conseils. Ils ont fait une énorme différence dans ma façon de rider.",
        "L'innovation et la technique que tu apportes au snowboard sont inspirantes. Cela montre qu'il y a toujours quelque chose de nouveau à apprendre."
    ];



    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {

        $userAdmin = new User();
        $password = $this->hasher->hashPassword($userAdmin, 'admin');

        $userAdmin->setUsername('admin')
            ->setPassword($password)
            ->setEmail('admin@admin.com')
            ->setFullName('AlexAdmin')
            ->setLinkUserPicture('default.jpg')
            ->setRoles(array('ROLE_ADMIN'))
            ->setIsVerified(1);
        $manager->persist($userAdmin);
        $manager->flush();

        $user = new User();
        $password2 = $this->hasher->hashPassword($user, 'user');

        $user->setUsername('user')
            ->setPassword($password2)
            ->setEmail('user@user.com')
            ->setFullName('AlexUser')
            ->setLinkUserPicture('default.jpg')
            ->setRoles(array('ROLE_USER'))
            ->setIsVerified(1);
        $manager->persist($user);
        $manager->flush();

        // other fixtures can get this object using the UserFixtures::ADMIN_USER_REFERENCE constant
        $this->addReference(self::ADMIN_USER_REFERENCE, $userAdmin);
        $this->addReference(self::USER_USER_REFERENCE, $user);


        for ($i = 0; $i <= count(self::$categoryNameArray) - 1; $i++) {
            $category = new Category();
            $category->setName(self::$categoryNameArray[$i])->setSlug(self::$categorySlugArray[$i]);
            $this->addReference(self::$categoryNameArray[$i], $category);
            $manager->persist($category);
        }
        $manager->flush();


        for ($i = 0; $i < (count(self::$categoryNameArray) - 1); $i++) {
            $trick = new Trick();
            $trick->setName(self::$trickNameArray[$i])
                ->setSlug(self::$trickSlugArray[$i])
                ->addCategory($this->getReference(self::$categoryNameArray[rand(0, 20)]))
                ->setDescription(self::$trickDescriptionArray[$i])
                ->setPublicationStatusTrick("Published")
                ->SetUser($this->getReference(self::ADMIN_USER_REFERENCE));
            $manager->persist($trick);

            for ($j = 1; $j <= rand(2, 4); $j++) {
                $picture = new Pictures();
                $picture->setPictureLink(self::$PictureNameList[rand(0, count(self::$PictureNameList) - 1)])
                    ->setPictureName("Image " . $j)
                    ->setTrick($trick)
                    ->setUser($this->getReference(self::ADMIN_USER_REFERENCE));
                $manager->persist($picture);
            }

            for ($j = 1; $j <= rand(2, 3); $j++) {
                $video = new Video();
                $video->setVideoLink(self::$videoLinkList[rand(0, (count(self::$videoLinkList) - 1))])
                    ->setVideoName("Vidéo " . $j)
                    ->setTrick($trick)
                    ->SetUser($this->getReference(AppFixtures::ADMIN_USER_REFERENCE));
                $manager->persist($video);
            }

            for ($j = 1; $j <= rand(11, 15); $j++) {
                $post = new Post();
                // Sélection aléatoire d'un commentaire de la liste
                $randomCommentIndex = array_rand(self::$commentList);
                $randomComment = self::$commentList[$randomCommentIndex];

                $post->setPostContent($randomComment)
                    ->setTrick($trick)
                    ->SetUser($this->getReference(AppFixtures::ADMIN_USER_REFERENCE));
                $manager->persist($post);
            }
        }
        $manager->flush();
    }
}