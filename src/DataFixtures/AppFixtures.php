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
        "Nose grab",
        "Japan",
        "Seat belt",
        "Truck driver",
        "Rotation",
        "180",
        "360",
        "540",
        "720",
        "900",
        "1080",
        "90",
        "270",
        "450",
        "630",
        "810",
        "Front flips",
        "Back flips",
        "Slide",
        "Nose slide",
        "Tail slide",
        "One foot tricks"
    ];

    private static array $categorySlugArray = [
        "nose-grab",
        "japan",
        "seat-belt",
        "truck-driver",
        "rotation",
        "180",
        "360",
        "540",
        "720",
        "900",
        "1080",
        "90",
        "270",
        "450",
        "630",
        "810",
        "front-flips",
        "back-flips",
        "slide",
        "nose-slide",
        "tail-slide",
        "one-foot-tricks"
    ];

    private static array $trickNameArray = [
        "Ollie",
        "Nollie",
        "Melon",
        "Indy",
        "Nose Grab",
        "50-50",
        "Tail Press",
        "Nose Press",
        "Frontside 180",
        "Backside 180",
        "Butter",
        "Tripod",
        "Tail Grab",
        "Boardslide",
        "Back Flip",
        "Frontside 360",
        "Backside 360",
        "Front Roll",
        "Front Flip",
        "Frontside 360+",
        "Backside 360+",
    ];

    private static array $trickSlugArray = [
        "ollie",
        "nollie",
        "melon",
        "indy",
        "nose-grab",
        "50-50",
        "tail-press",
        "nose-press",
        "frontside-180",
        "backside-180",
        "butter",
        "tripod",
        "tail-grab",
        "boardslide",
        "back-flip",
        "frontside-240",
        "backside-360",
        "front-roll",
        "front-flip",
        "frontside-360",
        "backside-290",
    ];

    private static array $trickDescriptionArray = [
        "Un Ollie est probablement le premier trick de snowboard que vous apprendrez. C'est votre introduction aux sauts en snowboard. Pour réaliser un Ollie, vous devez déplacer votre poids corporel sur votre jambe arrière. Sautez, en veillant à commencer par votre jambe avant. Soulevez votre jambe arrière en alignement avec votre jambe avant.",
        "Le Nollie est essentiellement l'opposé d'un Ollie. Lorsque vous sautez, commencez par votre jambe arrière. Ensuite, soulevez votre jambe avant pour aligner vos pieds. Vous trouverez probablement que vous pouvez gagner quelques centimètres après juste quelques essais.",
        "Quand vous prenez de l'air en snowboard, penchez-vous et attrapez le côté talon de la planche entre vos pieds. Félicitations, vous avez réalisé votre premier Melon !",
        "Vous pouvez réaliser un Indy en faisant un Ollie à partir d'un saut et en vous penchant pour attraper le bord des orteils de votre planche. Lâchez et repositionnez-vous pour un atterrissage en douceur.",
        "Si vous pouvez faire un Melon et un Indy, vous êtes prêt pour un nose grab. Pendant que vous êtes en l'air, penchez-vous pour attraper l'avant de votre planche. Redressez votre corps et préparez-vous pour un atterrissage facile.",
        "Le 50-50 vous initie aux tricks de glisse en snowboard. Quand vous approchez d'un rail ou d'une boîte, sautez pour atterrir dessus et glissez jusqu'à en sortir à l'autre extrémité. Commencez avec des rails courts jusqu'à ce que vous développiez l'équilibre nécessaire pour en glisser sur des plus longs.",
        "Pratiquez le tail press sur une surface plate où vous vous sentez à l'aise. Prenez un peu de vitesse avant de vous pencher en arrière pour déplacer votre poids sur votre jambe arrière. Vous pouvez lever votre jambe avant pour accentuer la courbure de votre snowboard.",
        "Le nose press fonctionne comme le tail press. Au lieu de vous pencher en arrière, vous déplacez votre poids vers l'avant. Cela peut sembler un peu plus intimidant au début, mais cela requiert la même compétence. La partie la plus difficile est de surmonter l'anxiété pour se sentir à l'aise sur son snowboard.",
        "Prêt à faire tourner votre snowboard ? Avec un frontside 180, vous faites tourner votre corps de sorte que vos talons mènent. Par exemple, si vous sautez en descendant la pente avec votre pied gauche devant, vous tourneriez dans le sens antihoraire pour un frontside 180. Arrêtez-vous lorsque votre jambe arrière devient votre jambe de tête.",
        "Un backside 180 est l'opposé d'un frontside 180. Tournez de sorte que vos orteils mènent. En descendant la pente avec votre jambe gauche devant, vous tourneriez dans le sens horaire jusqu'à ce que votre jambe droite devienne votre jambe de tête.",
        "Le butter demande un peu plus de force du tronc que le frontside 180 et le backside 180. Au lieu de faire passer votre jambe arrière devant pendant un saut, vous le faites tout en maintenant le contact avec la neige. La neige crée un peu plus de friction, alors préparez-vous à y mettre du muscle.",
        "Le tripod est un trick intermédiaire amusant à apprendre. Pour en réaliser un, vous devez soulever une extrémité de votre planche de la neige et vous pencher avec les deux mains pour toucher le sol. Quand vous le faites correctement, vous créez une connexion à trois points avec le sol, tout comme un trépied !",
        "La prochaine fois que vous prenez de l'air, tendez la main pour attraper la queue de votre snowboard.",
        "Le boardslide est comme un 50-50, sauf que vous tournez votre planche perpendiculairement au rail pour pouvoir glisser dessus de côté.",
        "Faites attention en essayant un backflip. Vous aurez besoin de beaucoup de temps et d'espace pour compléter le flip avant d'atterrir.",
        "La version cercle complet d'un Frontside 180.",
        "La version cercle complet d'un Backside 180.",
        "Le front roll déplace votre corps dans un mouvement vers l'avant, mais il s'incline un peu sur le côté. Maîtrisez-le avant de passer à un front flip complet.",
        "Le front flip est plus difficile que le backflip car vous devez résister au mouvement ascendant que vous obtenez de votre saut. Au lieu de cela, penchez-vous en avant et recroquevillez votre corps pour tourner vers l'avant.",
        "Vous l'avez probablement déjà deviné. C'est une rotation frontside qui vous fait tourner de plus d'un cercle complet.",
        "L'opposé d'un frontside 360+. Maintenir l'équilibre devient difficile lorsque vous reculez tout en tournant.",
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

            for ($j = 1; $j <= rand(1, 6); $j++) {
                $picture = new Pictures();
                $picture->setPictureLink(self::$PictureNameList[rand(0, count(self::$PictureNameList) - 1)])
                    ->setPictureName("Name of picture N°" . $j . " of trick n°" . $i)
                    ->setTrick($trick)
                    ->setUser($this->getReference(self::ADMIN_USER_REFERENCE));
                $manager->persist($picture);
            }

            for ($j = 1; $j <= rand(1, 3); $j++) {
                $video = new Video();
                $video->setVideoLink(self::$videoLinkList[rand(0, (count(self::$videoLinkList) - 1))])
                    ->setVideoName("Name of video N°" . $j . " of trick n°" . $i)
                    ->setTrick($trick)
                    ->SetUser($this->getReference(AppFixtures::ADMIN_USER_REFERENCE));
                $manager->persist($video);
            }


            for ($j = 1; $j <= rand(5, 15); $j++) {
                $post = new Post();
                $post->setPostContent("Discusion about trick n°" . $i . " post N°" . $j)
                    ->setTrick($trick)
                    ->SetUser($this->getReference(AppFixtures::ADMIN_USER_REFERENCE));
                $manager->persist($post);
            }
        }
        $manager->flush();
    }
}