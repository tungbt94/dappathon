<?php

namespace Module\Admin\Controller;

use Common\Util\Pql;
use Model\Article;
use Model\BaseModel;
use Model\Character;
use Model\Film;
use Model\FilmCharacter;
use Model\User;

class TestController extends ControllerBase
{
    public function indexAction()
    {
        $user = User::findFirstById(3);

//        dump($user->Usercreate);

        $testJoin = Film::builder('f')
            ->leftJoin(FilmCharacter::class, 'fc.film_id = f.id', 'fc')
            ->leftJoin(Character::class, 'c.id = fc.character_id', 'c')
            ->columns('f.name, c.fullname')
            ->where('f.status = :f_status:', ['f_status' => BaseModel::STATUS_ACTIVE])
//            ->andWhere('c.language = :c_language:', ['c_language' => 'en'])
            ->getQuery()
            ->execute();

//        dump($testJoin->toArray());

        $film = Film::findFirstById(1);
//        dump($film->Characters);
        d($film->Categories);
    }
}