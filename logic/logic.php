<?php
/* tego nie czytaj do odwolania bo to nudne i do szczescia ci niepotrzebne
	mi za to bardzo :) */
    ob_start();
	ini_set('error_reporting', E_ALL);
	function mres($r) { return mysql_real_escape_string($r); }
	function iof(&$r) { if ((int)($r) != $r) die($r.' nie jest liczba calkowita!'); $r = (int)$r; return $r; }

define('TRYBAR_UPLOADS_ROOT', 'uploads/');	// musi miec trailing slash
define('MAX_LAST_SHOUTS', 15);	// maks wpisow w shoutbox na usera
define('MESSAGES_PER_PAGE', 9);	// wiadomosci na strone w odbiorczej
define('MAX_LAST_AKTUA', 50);	// maks wpisow aktualnosci dla usera
define('MAX_RANKING_PER_PAGE', 5);	// maks wpisow rankingowych na strone (oba rankingi)
define('MAX_PRIZES_PER_PAGE', 10);   // maks wpisow nagrod na strone
define('LIMIT_USER_PHOTOS', 3);

/* na obiekty LogicInternal nie daje zadnej gwarancji ze sie nie zmienia.
	jesli korzystasz bezposrednio z nich, to sam prosisz sie o klopoty */

class LogicInternalGraphics {
	/* zwraca nowy obraz gd stanowiacy resize poprzedniego obrazu */
	static function resize($image, $target_width, $target_height) {
		// implementacja Trybal(c) Scaling Algorithm
		// by maślana
		$newimg = @imagecreatetruecolor($target_width, $target_height) or die('Sprawdź czy imagecreatetruecolor() dostepna!');
		
		$cur_width = imagesx($image);
		$cur_height = imagesy($image);
		
		// Piotruś...
		
		$cur_aspect_ratio = $cur_width/$cur_height;
		$tg_aspect_ratio = $target_width/$target_height;
		
		if ($tg_aspect_ratio == $cur_aspect_ratio) imagecopyresized($newimg, $image, 0, 0, 0, 0, $target_width, $target_height, $cur_width, $cur_height);
		else if ($tg_aspect_ratio > $cur_aspect_ratio) {	// target jest szerszy
		
			$sc_w = $cur_width;
			$sc_h = (int)($cur_width*$target_height / $target_width);		
			imagecopyresized($newimg, $image, 0, 0, 0, (int)(($cur_height-$sc_h)/2), $target_width, $target_height, $sc_w, $sc_h);
		
		} else {		// target jest wezszy

			$sc_w = (int)($cur_height*$target_height/$target_width);
			$sc_h = $cur_height;			
			imagecopyresized($newimg, $image, 0, 0, (int)(($cur_width-$sc_w)/2), 0, $target_width, $target_height, $sc_w, $sc_h);
		}
		// ... tatuś pracuje
		
		return $newimg;
	}
	
	/* alokuje zasób graficzny 
	0 - avatar, 1 - userphoto, 2 - barphoto, 3 - fota nagrody, 4 - banner, 5 - juve */
	static function allocResource($type) {
		mysql_query("INSERT INTO gfx VALUES (NULL, ".$type.")");
		return mysql_insert_id();
	}
	
	/* zwraca zasób GD z nazwy wpisu w $_FILES */
	static function getGDFromFile($filename) {
		return imagecreatefromstring(file_get_contents($_FILES[$filename]['tmp_name']));
	}

	/* masakra. Weź obraz $gd, przeskaluj go do zadanych wymiarów i następnie zapisz do katalogu pod identyfikatorem $id 
	   niezmiennie jako jot-peg */
	static function storeAs($gd, $id, $width, $height) {
		$newimg = LogicInternalGraphics::resize($gd, $width, $height);		
		imagejpeg($newimg, TRYBAR_UPLOADS_ROOT.$width.'x'.$height.'/'.$id.'.jpg');
	}
	
	/* zapisz natywnie $gd pod $id */
	static function storeAsNative($gd, $id) {
		imagejpeg($gd, TRYBAR_UPLOADS_ROOT.'native/'.$id.'.jpg');
	}
	
	/* zapisuje $gd pod $id z wysokoscia $h, szerokoscia zgodna z aspect ratio */
	static function storeAsCH($gd, $id, $h) {
		$cur_width = imagesx($gd);
		$cur_height = imagesy($gd);
		

		$new_width = (int)(($cur_width * $h) / $cur_height);

		$newimg = @imagecreatetruecolor($new_width, $h) or die('Sprawdź czy imagecreatetruecolor() dostepna!');
		
		imagecopyresized($newimg, $gd, 0, 0, 0, 0, $new_width, $h, $cur_width, $cur_height);		
		imagejpeg($newimg, TRYBAR_UPLOADS_ROOT.'stath'.$h.'/'.$id.'.jpg');				
	}
}

class LogicInternalAktualnosc {
	static function make($user, $atype, $arg1, $arg2, $arg3) {
		$arg1 = ($arg1 == null) ? "NULL" : $arg1;
		$arg2 = ($arg2 == null) ? "NULL" : $arg2;
		$arg3 = ($arg3 == null) ? "NULL" : $arg3;
			
		mysql_query('INSERT INTO aktualnosci VALUES (NULL, '.$user.', '.$atype.', NOW(), '.$arg1.', '.$arg2.', '.$arg3.')');
		mysql_query('DELETE FROM aktualnosci WHERE (user='.$user.') AND (id NOT IN (SELECT id FROM (SELECT id FROM aktualnosci WHERE user='.$user.' ORDER BY when_added DESC LIMIT '.MAX_LAST_AKTUA.') foo))');
	}
	
	static function repost($user_taking_action, $atype, $arg1, $arg2, $arg3) {
		$friends = array();
		$res = mysql_query('SELECT user1 FROM znajomy WHERE (user2='.$user_taking_action.') AND (confirmed=1)');
		while ($row = mysql_fetch_row($res)) LogicInternalAktualnosc::make($row[0], $atype, $arg1, $arg2, $arg3);
		$res = mysql_query('SELECT user2 FROM znajomy WHERE (user1='.$user_taking_action.') AND (confirmed=1)');
		while ($row = mysql_fetch_row($res)) LogicInternalAktualnosc::make($row[0], $atype, $arg1, $arg2, $arg3);
	}
/*			atype	=	0:		Użytkownik $arg1 został stałym bywalem w barze $arg2		[ DONE ]
			atype	=	1:		Użytkownik $arg1 dodał komentarz do baru $arg2				[ DONE ]
			atype	=	2:		Użytkownik $arg1 dodał komentarz do zdjęcia (ModelUserPhoto $arg2) użytkownika $arg3	[ DONE ]
			atype	=	3:		Użytkownik $arg1 dodał bar $arg2 o zdjeciu gfx id $arg3		[ DONE ]
			atype	=	4:		Użytkownik $arg1 dodał zdjęcie gfx $arg2 do baru $arg3		[ DONE ]
			atype	=	5:		Użytkownik $arg1 dodał sobie fotkę (ModelUserPhoto $arg2)	[ DONE ]
			atype	=	6:		Użytkownik $arg1 został znajomym usera $arg2				[ DONE ]
            atype   =   7:      Użytkownik $arg1 wybrał nagrodę $arg2                       [ DONE ]
            
            */
	
    static function delete_prizes_by_user($id) {
		mysql_query('DELETE FROM aktualnosci WHERE (atype=7) and (arg1='.$id.')');
    }

    static function delete_prizes_by_prize($id) {
		mysql_query('DELETE FROM aktualnosci WHERE (atype=7) and (arg2='.$id.')');
    }

    
	static function delete_userphoto($id) {
		mysql_query('DELETE FROM aktualnosci WHERE (atype=2) and (arg2='.$id.')');
		mysql_query('DELETE FROM aktualnosci WHERE (atype=5) and (arg2='.$id.')');
	}
	
	static function delete_barphoto_by_gfx($gfx) {
		mysql_query('DELETE FROM aktualnosci WHERE (atype=4) AND (arg2='.$gfx.')');
		mysql_query('UPDATE aktualnosci SET arg3=0 WHERE (atype=3) AND (arg3='.$gfx.')');
	}
	
	static function delete_userphotocomment_by_x($ph, $commenter) {
		mysql_query('DELETE FROM aktualnosci WHERE (atype=2) AND (arg2='.$ph.') AND (arg1='.$commenter.')');
	}
}

class LogicInternalShoutbox {
	/* zwraca $x wpisów shoutboxa dla usera $uid */
	static function getEntries($uid, $x) {
		$res = mysql_query('SELECT shout.shouter, shout.when_added, shout.content, user.login, user.lastping FROM shout INNER JOIN user ON shout.shouter=user.id WHERE shout.shoutee='.$uid.' ORDER BY when_added DESC LIMIT '.$x);
		
		$e = array();
		while ($row = mysql_fetch_row($res)) {
			$row[4] = (time() - strtotime($row[4])) < 20;
			$e[] = $row;
		}
		return $e;
	}
	
	/* uzytkownik o id $id krzyczy trescia $content */
	static function shout($id, $content) {
		/* pobierz wszystkch znajomych */
		$znajomi_id = array();
		$content = strip_tags($content);
		$res = mysql_query('SELECT user1 FROM znajomy WHERE (user2='.$id.') AND (confirmed=1)');
		while ($row = mysql_fetch_row($res)) $znajomi_id[] = $row[0];
		$res = mysql_query('SELECT user2 FROM znajomy WHERE (user1='.$id.') AND (confirmed=1)');
		while ($row = mysql_fetch_row($res)) $znajomi_id[] = $row[0];
		$znajomi_id[] = $id;
		
		/* do kazdego znajomego wyslij */
		foreach ($znajomi_id as $znajomy) {
			mysql_query('INSERT INTO shout VALUES (NULL, '.$id.', '.$znajomy.', NOW(), "'.mres($content).'")');
		}
	}
}

class LogicInternalBar {
	/* przelicza srednia ocen baru */
	static function remakeMarkCache($barid) {
		mysql_query('DELETE FROM cache_bar_mark WHERE bar='.$barid);
		mysql_query('INSERT INTO cache_bar_mark (SELECT '.$barid.', AVG(o0), AVG(o1), AVG(o2), '.
					'AVG(o3), AVG(o4), AVG(o5), AVG(o6), AVG(o7), AVG(o8), AVG(o9), AVG(o10), '.
					'AVG(o11), AVG(o12) FROM bar_mark WHERE bar='.$barid.')');
	}
	
	/* regeneruje wewnętrzny ranking barów
	   ! operacja bardzo kosztowna ! */
	static function regenerateRanking() {
		mysql_query('TRUNCATE TABLE cache_ranking_bar');
		$res = mysql_query('SELECT bar, o0, o1, o2, o3, o4, o5, o6, o7, o8, o9, o10, o11, o12 FROM cache_bar_mark');
		
		$bary = array();
		while ($row = mysql_fetch_row($res)) {

            /* sprawdz ile ocen ma bar ! */
            $res2 = mysql_query('SELECT COUNT(*) FROM bar_mark WHERE bar='.$row[0]);
            $row2 = mysql_fetch_row($res2);
            
            if ($row2[0] < 3) continue;

				/* liczymy ocene */
			$count = 0;
			$sum = 0;
			for ($i = 1; $i < 14; $i++)	if ($row[$i] != null) { $sum += $row[$i]; $count++; }
			
			if ($count == 0) continue;
			
			$bary[$row[0]] = array($sum, $count);
		}
		
        mysql_query('CREATE TEMPORARY TABLE IF NOT EXISTS sortowanie_barow ( id INT, srednia FLOAT, oceny INT, nazwa VARCHAR(40) )');
        mysql_query('TRUNCATE TABLE sortowanie_barow');
        foreach ($bary as $bar_id => $mark) {
            $extra_data_res = mysql_query('SELECT name FROM bar WHERE id='.$bar_id);
            $nazwa = mysql_fetch_array($extra_data_res);

            mysql_query('INSERT INTO sortowanie_barow VALUES ('.$bar_id.', ROUND('.($mark[0]/$mark[1]).',2), '.$mark[1].', "'.mres($nazwa['name']).'")');
        }

        $res = mysql_query('SELECT id FROM sortowanie_barow ORDER BY srednia DESC, oceny DESC, nazwa ASC');
        $i = 1;
        while ($row = mysql_fetch_array($res)) {
            mysql_query('INSERT INTO cache_ranking_bar VALUES ('.$i.', '.$row['id'].')');
            $i++;
        }

        mysql_query('DROP TABLE sortowanie_barow');
		
	}
}

class LogicInternalUser {
	/* regeneruje wewnętrzny ranking użytkowników
	   ! operacja bardzo kosztowna ! */
	static function regenerateRanking() {
		mysql_query('TRUNCATE TABLE cache_ranking_user2');
		$res = mysql_query('SELECT id, pointsC+pointsP FROM user WHERE (active=1) AND ((pointsC+pointsP)>0) ORDER BY (pointsC+pointsP) DESC, login ASC');
		
		$i = 1;
		while ($row = mysql_fetch_row($res)) { 
			mysql_query('INSERT INTO cache_ranking_user2 VALUES ('.$i++.', '.$row[0].', '.$row[1].')');			
		}
	}
	
	/* pointify */
	static function addPoints($uid, $pts) {	mysql_query('UPDATE user SET pointsC=pointsC+'.$pts.' WHERE id='.$uid); }
	static function delPoints($uid, $pts) {	mysql_query('UPDATE user SET pointsC=pointsC-'.$pts.' WHERE id='.$uid); }

	static function ping() {
		$uid = LogicSession::getUserId();
		mysql_query('UPDATE user SET lastping=NOW() WHERE id='.$uid);
	}

}

/* dalszy kod mozesz czytac bo on moze cie interesowac */

class LogicMessage {
	/* sprawdza czy wiadomosc prywatna o $id istnieje */
	function existsId($id) {
        $id = iof($id);
		$res = mysql_query('SELECT COUNT(*) FROM messages WHERE id='.$id);
		$row = mysql_fetch_row($res);
		return ($row[0] > 0);
	}
}

class LogicContact {
	/* skrzynka kontaktowa z tytulem $email, $title i trescia $content */
	static function contact($email, $title, $content) {
		mail('trybar@trybar.pl', 'Skrzynka kontaktowa - '.$title, $content."\n\nWiadomość od: ".$email);
	}
}

class ModelBanner {
    static function exists($id) {
        /* sprawdza czy baner o danym ID istnieje */
        $id = iof($id);
        $res = mysql_query('SELECT COUNT(*) FROM banners WHERE id='.$id);
        return mysql_num_rows($res) > 0;
    }

    /* wczytuje baner o zadanym $id */
    function __construct($id) {
        iof($id);
		$res = mysql_query('SELECT * FROM banners WHERE id='.$id);
		$row = mysql_fetch_array($res);
        
		$this->id = $id;
		$this->id_gfx = $row['id_gfx'];
		$this->alttext = $row['alttext'];
		$this->url = $row['url'];
		$this->clicks = $row['clicks'];
		$this->displays = $row['displays'];                
		$this->page = $row['page'];        
    }
    
    /* kasuje dany baner */
    function delete()
    {
        mysql_query('DELETE FROM gfx WHERE id='.$this->id_gfx);
        mysql_query('DELETE FROM banners WHERE id='.$this->id);
    }
    
    /* wybiera baner do wyswietlenia na ta strone i podbija mu displays.
        zwraca null jeśli nie ma co wyświetlić
        zwraca ModelBanner jak cos wyswietli */
    static function get_to_display($page) {
        iof($page);
        $res = mysql_query('SELECT id FROM banners WHERE (page='.$page.') ORDER BY displays ASC LIMIT 0, 1');
        $row = mysql_fetch_array($res);
        if (!$row) return NULL;
        $id = $row['id'];
        
        mysql_query('UPDATE banners SET displays=displays+1 WHERE id='.$id);
        
        $x = new ModelBanner($id);
        return $x;
    }    
    
    /* informuje ze baner o danym $id zostal klikniety */
    function was_clicked() {
        mysql_query('UPDATE banners SET clicks=clicks+1 WHERE id='.$this->id);        
    }
    
    /* dodaje baner o zadanym obrazku (gfx) i parametrach
       zwraca ID baneru */
    static function create($alttext, $url, $gfx, $page) {
        iof($page);
        mysql_query('INSERT INTO banners VALUES (NULL, '.$gfx.', "'.mres($alttext).'", "'.mres($url).'", 0, 0, '.$page.')');
        return mysql_insert_id();
    }    
    
    /* zwraca liste wszystkich banerow(ModelBanner) w bazie */
    static function get_all_banners() {
        $res = mysql_query('SELECT id FROM banners');
        $banners = array();
        while ($row = mysql_fetch_array($res)) $banners[] = new ModelBanner($row['id']);
        return $banners;
    }
}

class ModelPrize {
	/* wczytuje nagrode o zadanym $id */
	function __construct($id) {
        iof($id);
		$res = mysql_query('SELECT * FROM prizes WHERE id='.$id);
		$row = mysql_fetch_array($res);
		
		$this->id = $id;
		$this->name = $row['name'];
		$this->gfx = $row['gfx'];
		$this->category = $row['category'];
        $this->url = $row['url'];
    }
    
    /* hał meny */
    function howMany() {
        $res = mysql_query('SELECT COUNT(*) FROM user WHERE prize_id='.$this->id);
        $row = mysql_fetch_row($res);
        return $row[0];
    }
    
    /* user o id $usr wybiera wlasnie ta nagrode */
    function pick($usr) {
        LogicInternalAktualnosc::delete_prizes_by_user($usr);
        mysql_query('UPDATE user SET prize_id='.$this->id.' WHERE id='.iof($usr));
        LogicInternalAktualnosc::repost($usr, 7, $usr, $this->id, 0);
    }
    
    /* kasuje */
    function delete() {
        LogicInternalAktualnosc::delete_prizes_by_prize($this->id);
        mysql_query('DELETE FROM gfx WHERE id='.$this->gfx);
        mysql_query('DELETE FROM prizes WHERE id='.$this->id);
    }
}

class LogicPrize {
    /* dodaje nagrode o zadanym obrazku (gfx), nazwie i kategorii(ktora jest intem)
       zwraca ID dodanej nagrody */
    static function create($name, $category, $gfx, $url) {
        mysql_query('INSERT INTO prizes VALUES (NULL, "'.mres($name).'", '.$gfx.', '.$category.', "'.mres($url).'")');
        return mysql_insert_id();
    }
    
    /* zwraca ilosc nagrod w danej kategorii */
    static function pagesInCategory($category) {
        $res = mysql_query('SELECT COUNT(*) FROM prizes WHERE category='.$category);
        $row = mysql_fetch_row($res);
        return $row[0];
    }
    
    /* zwraca wsie nagrody z kategorii jako liste ModelPrize */
    static function pageByAll($category) {
        $res = mysql_query('SELECT id FROM prizes WHERE category='.$category);
        $ps = array();
        while ($row = mysql_fetch_row($res)) $ps[] = new ModelPrize($row[0]);
        return $ps;
    }
    
    /* zwraca MAX_PRIZES_PER_PAGE nagrod ze strony o numerze $pgno - strony numerujemy od 1
       zwraca liste ModelPrize */
    static function pageBy($category, $pgno) {
        $pgno -= 1;
        $res = mysql_query('SELECT id FROM prizes WHERE category='.$category.' LIMIT '.$pgno*MAX_PRIZES_PER_PAGE.', '.MAX_PRIZES_PER_PAGE);
        $ps = array();
        while ($row = mysql_fetch_row($res)) $ps[] = new ModelPrize($row[0]);
        return $ps;
    }
}


class ModelAktualnosc {
	/* wczytuje aktualnosc o zadanym $id */
	function __construct($id) {
        iof($id);
		$res = mysql_query('SELECT * FROM aktualnosci WHERE id='.$id);
		$row = mysql_fetch_array($res);
		
		$this->id = $id;
		$this->user = $row['user'];	// user dla ktorego przeznaczona jest aktualnosc
		$this->atype = $row['atype'];
		$this->when_added = strtotime($row['when_added']);
		$this->arg1 = $row['arg1'];
		$this->arg2 = $row['arg2'];
		$this->arg3 = $row['arg3'];
	}
	/* 	Podreczny Spis Aktualnosci	( jesli mowa o awatarach, fotach czy grafikach to poslugujemy sie ich id $arg. Jesli to gfx jest null to znaczy ze ich nie ma )
	
			atype	=	0:		Użytkownik $arg1 został stałym bywalem w barze $arg2		[ DONE ]
			atype	=	1:		Użytkownik $arg1 dodał komentarz do baru $arg2				[ DONE ]
			atype	=	2:		Użytkownik $arg1 dodał komentarz do zdjęcia (ModelUserPhoto $arg2) użytkownika $arg3	[ DONE ]
			atype	=	3:		Użytkownik $arg1 dodał bar $arg2 o zdjeciu gfx id $arg3		[ DONE ]
			atype	=	4:		Użytkownik $arg1 dodał zdjęcie gfx $arg2 do baru $arg3		[ DONE ]
			atype	=	5:		Użytkownik $arg1 dodał sobie fotkę (ModelUserPhoto $arg2)	[ DONE ]
			atype	=	6:		Użytkownik $arg1 został znajomym usera $arg2				[ DONE ]
	*/
}

class ModelShout {
	/* tworzy shouta o zadanym $id */
	function __construct($id) {
        iof($id);
		$res = mysql_query('SELECT * FROM shout WHERE id='.$id);
		$row = mysql_fetch_array($res);
		
		$this->id = $row['id'];
		$this->shouter = $row['shouter'];	// kto krzyczy
		$this->shoutee = $row['shoutee'];	// na kogo
		$this->when_added = strtotime($row['when_added']);	// kiedy dodano
		$this->content = $row['content'];	// tresc krzyku		
	}
}

class LogicShoutbox {
	/* pobiera N ostatnich shoutow uzytkownika o id $id
	   sa one posortowane od najmlodszego do najstarszego */
	static function getNLastShouts($n, $id) {
		$res = mysql_query('SELECT id FROM shout WHERE shoutee='.$id.' ORDER BY when_added DESC LIMIT 0, '.$n);
		$shouts = array();
		while ($row = mysql_fetch_row($res)) $shouts[] = new ModelShout($row[0]);
		return $shouts;	
	}
}

// klasa opisujaca zestaw ocen dla baru wystawiona na konkretny bar przez konkretnego uzytkownika
class ModelMark {
	/* $bar to id baru, $user to id usera */
	function __construct($bar, $user) {
        iof($bar); iof($user);
		$this->bar = $bar;
		$this->user = $user;
		$this->marks = array();
		$res = mysql_query('SELECT * FROM bar_mark WHERE (user='.$user.') AND (bar='.$bar.')');
		if (mysql_num_rows($res) == 0) {
			$this->exists = false;
			return;
		}
		$this->exists = true;
		$row = mysql_fetch_array($res);
		for ($i=0; $i < 13; $i++) $this->marks[$i] = $row['o'.$i];
	}

	/* pobiera ocene o kategorii id. Kategorie Oceny numerujemy od 0 do 13.
		zwraca albo liczbe albo null, jesli brak oceny */
	function getMark($cat) {
		if (array_key_exists($cat, $this->marks))
			return $this->marks[$cat];
		else
			return null;
	}

	/* ustawia ocene kategorii id jako wartosc $val. Wartosc moze byc albo liczba albo null */
	function setMark($id, $val) {
		$this->marks[$id] = $val;
		$mval = ($val == null) ? 'NULL' : $val;
		
		if ($this->exists) {
			mysql_query('UPDATE bar_mark SET o'.$id.'='.$mval.' WHERE (user='.$this->user.') AND (bar='.$this->bar.')');
		} else {
			mysql_query('INSERT INTO bar_mark (bar, user, o'.$id.') VALUES ('.$this->bar.', '.$this->user.', '.$mval.')');
			$this->exists = true;
		}
		LogicInternalBar::remakeMarkCache($this->bar);
	}
}


class LogicUpload {
	/* sprawdza czy zadany plik graficzny spelnia wymagania. $filename to nazwa wpisu w tabeli $_FILES, a $filesize to max rozmiar pliku w bajtach.
	   0 - plik OK
	   1 - plik za duży
	   2 - niepoprawny plik graficzny / nierozpoznawany format
	   3 - blad uploadowania */
	static function checkValidity($filename, $filesize) {
		if ($_FILES[$filename]['error'] != 0) return 3;
		if ($_FILES[$filename]['size'] > $filesize) return 1;
		if (!@imagecreatefromstring(file_get_contents($_FILES[$filename]['tmp_name']))) return 2;
		return 0;
	}

	/* zapisuje obrazek ($filename nazwa wpisu w $_FILES) jako typ
	   0 - avatar, 1 - fota usera, 2 - fota baru, 3 - fota nagrody, 4 - fota baneru, 5 - fota juve
       zwraca identyfikator obrazu $gfx*/
	static function storeAs($filename, $type) {
		$img = LogicInternalGraphics::getGDFromFile($filename);
		$id = LogicInternalGraphics::allocResource($type);
		
		switch ($type) {
			case 0:
				LogicInternalGraphics::storeAs($img, $id, 50, 50);
				LogicInternalGraphics::storeAs($img, $id, 84, 84);
				LogicInternalGraphics::storeAs($img, $id, 100, 100);
				LogicInternalGraphics::storeAs($img, $id, 140, 140);
				LogicInternalGraphics::storeAs($img, $id, 140, 158);
				break;
			case 1:
				LogicInternalGraphics::storeAs($img, $id, 206, 154);
				LogicInternalGraphics::storeAs($img, $id, 50, 50);
				LogicInternalGraphics::storeAsNative($img, $id);
				LogicInternalGraphics::storeAsCH($img, $id, 50);
				break;
			case 2:
				LogicInternalGraphics::storeAs($img, $id, 206, 154);
				LogicInternalGraphics::storeAs($img, $id, 180, 132);
				LogicInternalGraphics::storeAs($img, $id, 175, 117);
				LogicInternalGraphics::storeAs($img, $id, 66, 49);
				LogicInternalGraphics::storeAs($img, $id, 150, 113);
				LogicInternalGraphics::storeAs($img, $id, 167, 77);
				LogicInternalGraphics::storeAs($img, $id, 50, 50);
				LogicInternalGraphics::storeAsNative($img, $id);
				LogicInternalGraphics::storeAsCH($img, $id, 50);
				LogicInternalGraphics::storeAsCH($img, $id, 132);
				break;
            case 5:
				LogicInternalGraphics::storeAs($img, $id, 206, 154);
				LogicInternalGraphics::storeAs($img, $id, 180, 132);
				LogicInternalGraphics::storeAs($img, $id, 175, 117);
				LogicInternalGraphics::storeAs($img, $id, 66, 49);
				LogicInternalGraphics::storeAs($img, $id, 150, 113);
				LogicInternalGraphics::storeAs($img, $id, 167, 77);
				LogicInternalGraphics::storeAs($img, $id, 50, 50);
				LogicInternalGraphics::storeAsNative($img, $id);
				LogicInternalGraphics::storeAsCH($img, $id, 50);
				LogicInternalGraphics::storeAsCH($img, $id, 132);
				break;
            case 3:
                LogicInternalGraphics::storeAs($img, $id, 50, 50);
                LogicInternalGraphics::storeAs($img, $id, 200, 200);
                break;
            case 4:
                LogicInternalGraphics::storeAsNative($img, $id);
                break;
		}
		return $id;
	}
}

class ModelUserPhotoComment {
	/* laduje koment o zadanym id */
	function __construct($id) {
		iof($id);
		$res = mysql_query('SELECT * FROM userphoto_comment WHERE id='.$id);
		$row = mysql_fetch_array($res);
		
		$this->id = $row['id'];
		$this->photo = $row['photo']; // id UserPhoto
		$this->user = $row['user'];		// id usera wystawiajacego
		$this->comment = $row['comment'];		// tekst komentarza
		$this->when_added = strtotime($row['when_added']);	// kiedy dodano
	}
	
	/* kasuje komenta */
	function delete() {
		LogicInternalUser::delPoints($this->user, 1);
		LogicInternalAktualnosc::delete_userphotocomment_by_x($this->photo, $this->user);
		
		mysql_query('DELETE FROM userphoto_comment WHERE id='.$this->id);
	}
}

class ModelUserPhoto {
	/* laduje fote o zadanym ID */
	function __construct($id) {
        iof($id);
		$res = mysql_query('SELECT * FROM userphoto WHERE id='.$id);
		if (mysql_num_rows($res) == 0) die('Nieistniejace zdjecie, id='.$id);
		$row = mysql_fetch_array($res);
		
		$this->id = $row['id'];
		$this->user = $row['user'];
		$this->gfx = $row['gfx'];
		$this->description = $row['description'];
	}
	
	/* morduje fote */
	function delete() {
		mysql_query('DELETE FROM aktualnosci WHERE (atype=2) AND (arg2='.$this->id.')');
		mysql_query('DELETE FROM aktualnosci WHERE (atype=5) AND (arg2='.$this->id.')');
			
		$res = mysql_query('SELECT user FROM userphoto_comment WHERE photo='.$this->id);
		while ($row = mysql_fetch_row($res)) LogicInternalUser::delPoints($row[0], 1);
		mysql_query('DELETE FROM userphoto_comment WHERE photo='.$this->id);
		
		mysql_query('DELETE FROM gfx WHERE id='.$this->gfx);
		mysql_query('DELETE FROM userphoto WHERE id='.$this->id);
		
		LogicInternalUser::delPoints($this->user, 5);
		LogicInternalAktualnosc::delete_userphoto($this->id);
	}
	
	/* laduje liste komentarzy - taki komentarz to instancja ModelUserPhotoComment */
	function getComments() {
		$res = mysql_query('SELECT id FROM userphoto_comment WHERE photo='.$this->id.' ORDER BY when_added DESC');
		$comments = array();
		while ($row = mysql_fetch_row($res)) $comments[] = new ModelUserPhotoComment($row[0]);
		return $comments;
	}
	/* uzytkownik o id $id komentuje ta fote trescia $comment */
	function comment($id, $comment) {
		iof($id);
		mysql_query('INSERT INTO userphoto_comment VALUES (NULL, '.$this->id.', '.$id.', "'.mres($comment).'", NOW())');
		
		LogicInternalUser::addPoints($id, 1);
		LogicInternalAktualnosc::repost($id, 2, $id, $this->id, $this->user);
	}
}

class ModelMessage {
	/* inicjuje wiadomosc */
	function __construct($id) {
        iof($id);
		$res = mysql_query("SELECT * FROM messages WHERE id=".$id);
		$row = mysql_fetch_array($res);
		
		$this->id = $id;
		$this->sender = $row['sender'];
		$this->receiver = $row['receiver'];
		$this->title = $row['title'];
		$this->content = $row['content'];
		$this->readed = ($row['readed'] == 1);
		$this->when_sent = strtotime($row['when_sent']);
		$this->functional = $row['functional'];
	}
	
	/* oznacza jako przeczytana - wywoluj zawsze jak jest czytana */
	function markReaded() {
		mysql_query('UPDATE messages SET readed=1 WHERE id='.$this->id);
	}
	
	/* jesli jest to zwykla wiadomosc zwraca null, w przeciwnym wypadku zwraca id usera ktory prosi nas zebysmy zostali ich znajomym */
	function getUserAsking() {
		if ($this->functional == 0) return null;
	
			// user2 to zawsze osoba proszona, a user1 - proszaca
	
		$res = mysql_query('SELECT user1 FROM znajomy WHERE id='.$this->functional);
		$row = mysql_fetch_row($res);
		return $row[0];
	}
	
	/* jesli jest to wiadomosc z prosba o akceptacje, realizuje ta akceptacje i usuwa ta wiadomosc */
	function acceptInvitation() {
		if ($this->functional == 0) die('Wiadomość zwykła');
		
		mysql_query('UPDATE znajomy SET confirmed=1 WHERE id='.$this->functional);
		$this->delete();
	}

	/* jesli to jest wiadomosc z prosba o akceptacje, odrzuca akceptacje i usuwa wiadomosc */
	function rejectInvitation() {
		if ($this->functional == 0) die('Wiadomość zwykła');
		
		mysql_query('DELETE znajomy WHERE id='.$this->functional);
		$this->delete();
	}
	
	/* kasuje wiadomosc */
	function delete() {
		mysql_query('DELETE FROM messages WHERE id='.$this->id);
	}
}

class ModelBarComment {
	/* id musi byc poprawne i istniejace, inaczej zachowanie niezdefiniowane */
	function __construct($id) {
		iof($id);
		$res = mysql_query("SELECT * FROM bar_comment WHERE id=".$id);
		$row = mysql_fetch_array($res);
		
		$this->id = $id;
		$this->bar = $row['bar'];
		$this->user = $row['user'];
		$this->content = $row['content'];
		$this->when_added = strtotime($row['when_added']);
	}
	
	function delete() {
		LogicInternalUser::delPoints($this->user, 1);
		mysql_query('DELETE FROM bar_comment WHERE id='.$this->id);
	}
}		

class ModelJuveComment {
	/* id musi byc poprawne i istniejace, inaczej zachowanie niezdefiniowane */
	function __construct($id) {
		iof($id);
		$res = mysql_query("SELECT * FROM juve_comment WHERE id=".$id);
		$row = mysql_fetch_array($res);
		
		$this->id = $id;
		$this->user = $row['user'];
		$this->when_added = strtotime($row['when_added']);
		$this->content = $row['content'];
	}
    
    /* zwraca wszystkie komentarze posortowane datą, rosnąco. zwraca liste obiektow ModelJuveComment */
    static function get_all() {
        $res = mysql_query('SELECT id FROM juve_comment ORDER BY when_added ASC');
        $comments = array();
        while ($row = mysql_fetch_array($res)) { 
            $c = new ModelJuveComment($row[0]);
            $comments[] = $c;
        }
        return $comments;
    }
	
	function delete() {
		mysql_query('DELETE FROM juve_comment WHERE id='.$this->id);
	}
    
    /* dodaje komentarz */
    static function create($user, $content) {
        mysql_query('INSERT INTO juve_comment VALUES (NULL, '.$user.', NOW(), "'.mres($content).'")');
    }
    
    /* sprawdza czy istnieje */
    static function exists($id) {
        iof($id);
        $res = mysql_query('SELECT COUNT(*) FROM juve_comment WHERE id='.$id);
        $row = mysql_fetch_row($res);
        return ($row[0] == 1);
    }
}		

class Juve {
    // ocen
    static function mark($user, $value) {
        iof($user); iof($value);
        
        if (($value == 0) || ($value == null)) {
            mysql_query('DELETE FROM juve_mark WHERE user='.$user);
            return;
        }
        
        if ($value < 1) return;
        if ($value > 10) return;
        
        $res = mysql_query('SELECT COUNT(*) FROM juve_mark WHERE user='.$user);
        $row = mysql_fetch_row($res);
        if ($row[0] == 0) // nie ocenial
            mysql_query('INSERT INTO juve_mark VALUES ('.$user.', '.$value.')');
        else
            mysql_query('UPDATE juve_mark SET value='.$value.' WHERE user='.$user);
    }
    
    // ile osob glosowano
    static function howManyVoted() {
        $res = mysql_query('SELECT COUNT(*) FROM juve_mark');
        $row = mysql_fetch_row($res);
        return $row[0];
    }
    
    // srednia ocena
    static function avgMark() {
        $res = mysql_query('SELECT AVG(value) FROM juve_mark');
        $row = mysql_fetch_row($res);
        if ($row[0] == NULL) return '0';
        return $row[0];
    }
    
    // pobiera ocene danej osoby
    static function getPersonsMark($uid) {
        iof($uid);
        $res = mysql_query('SELECT value FROM juve_mark WHERE user='.$uid);
        $row = mysql_fetch_row($res);
        if (!$row) return 0;
        return $row[0];
    }
    
    // dodaj obrazek o zadanym gfx_id
    static function addImage($gfx) {
        iof($gfx);
        mysql_query('INSERT INTO juve_photo VALUES ('.$gfx.')');
    }
    
    // usun obrazek o zadanym gfx_id
    static function deleteImage($gfx) {
        iof($gfx);
        mysql_query('DELETE FROM juve_photo WHERE gfx='.$gfx);
    }
    
    // pobiera liste wszystkich gfx id
    static function getImages() {
        $res = mysql_query('SELECT gfx FROM juve_photo');
        $ids = array();
        while ($row = mysql_fetch_row($res)) $ids[] = $row[0];
        return $ids;
    }

    // pobiera liste wszystkich bywalcow user id
    static function getBywalcy() {
        $res = mysql_query('SELECT uid FROM juvebywalec');
        $ids = array();
        while ($row = mysql_fetch_row($res)) $ids[] = $row[0];
        return $ids;
    }

    // sprawdza czy bywalec
    static function isBywalec($uid) {
        iof($uid);
        $res = mysql_query('SELECT COUNT(*) FROM juvebywalec WHERE uid='.$uid);
        $row = mysql_fetch_row($res);
        return ($row[0] == 1);
    }
    
    // ustawia użytkownika jako bywalca
    static function setBywalec($uid) {
        iof($uid);
        mysql_query('INSERT INTO juvebywalec VALUES ('.$uid.')');        
    }
    
    // odpisuje uzytkownika jako bywalca
    static function unsetBywalec($uid) {
        iof($uid);
        mysql_query('DELETE FROM juvebywalec WHERE uid='.$uid);
    }
}

class ModelBar {
	/* inicjuje bar z zadanego id */
	function __construct($id) {
		iof($id);	
		$res = mysql_query("SELECT * FROM bar WHERE id=".$id);
		$row = mysql_fetch_array($res);
		
		$this->id = $id;
		$this->name = $row['name'];
		$this->street = $row['street'];
		$this->city = $row['city'];
		$this->region = $row['region'];
		$this->photo = $row['photo'];		// barphoto.id, 0 jesli brak
		$this->description = $row['description'];
		$this->when_added = strtotime($row['when_added']);
		$this->user = $row['user'];	// id usera ktory dodal
	}
	
	/* ustawia ulice na $street */
	function setStreet($street) {
		$this->street = $street;
		mysql_query('UPDATE bar SET street="'.mres($street).'" WHERE id='.$this->id);
	}
	
	/* ustawia opis na $desc */
	function setOpis($desc) {
		$this->description = $desc;
		mysql_query('UPDATE bar SET description="'.mres($desc).'" WHERE id='.$this->id);
	}
	
	/* kasuje fote o gfx $gfx */
	function deletePhoto($gfx) {
        iof($gfx);
		LogicInternalUser::delPoints(LogicSession::getUserId(), 20);
		LogicInternalAktualnosc::delete_barphoto_by_gfx($gfx);
	
		$deleting_main = ($gfx == $this->getFirstPhoto());
	
		mysql_query('DELETE FROM gfx WHERE id='.$gfx);
		mysql_query('DELETE FROM barphoto WHERE (gfx='.$gfx.') AND (bar='.$this->id.')');
		
		$res = mysql_query('SELECT COUNT(*) FROM barphoto WHERE bar='.$this->id);
		$row = mysql_fetch_row($res);
		if ($row[0] == 0) {
			mysql_query('UPDATE bar SET photo=0 WHERE id='.$this->id);
			$this->photo = 0;
			return;
		}
		
		if ($deleting_main) {
			$res = mysql_query('SELECT id FROM barphoto WHERE bar='.$this->id.' LIMIT 1');
			$row = mysql_fetch_row($res);
			$this->photo = $row[0];
			mysql_query('UPDATE bar SET photo='.$row[0].' WHERE id='.$this->id);
		}
	}
	
	/* pobiera ilosc ocen */
	function getMarkCout() {
		$res = mysql_query('SELECT COUNT(*) FROM bar_mark WHERE bar='.$this->id);
		$row = mysql_fetch_row($res);
		return $row[0];
	}
	
	/* ustawia zdjecie o gfx $gfx jako glowne. zdjecie musi byc zarejestrowane na liscie zdjec baru */
	function setAsMainPhoto($gfx) {
        iof($gfx);
		$res = mysql_query('SELECT id, bar FROM barphoto WHERE gfx='.$gfx);
		if (mysql_num_rows($res) == 0) die('Zdjecie niezarejestrowane na liscie zdjec baru!');
		$row = mysql_fetch_row($res);
		if ($row[1] != $this->id) die('Zdjecie nie nalezy do tego baru!');
		mysql_query('UPDATE bar SET photo='.$row[0].' WHERE id='.$this->id);
	}
		
	/* dodaje zdjecie o gfx $gfx do listy zdjec tego baru */
	function addPhoto($gfx) {
        iof($gfx);
		$res = mysql_query('SELECT COUNT(*) FROM barphoto WHERE gfx='.$gfx);
		$row = mysql_fetch_row($res);
		if ($row[0] != 0) die('Nie mozna dodac tego samego zdjecia dwa razy!');
		mysql_query('INSERT INTO barphoto VALUES(NULL, '.$this->id.', '.$gfx.')');
		
		if ($this->photo == 0) {
			$this->photo = mysql_insert_id();
			mysql_query('UPDATE bar SET photo='.$this->photo.' WHERE id='.$this->id);
		}
		
		LogicInternalAktualnosc::repost(LogicSession::getUserId(), 4, LogicSession::getUserId(), $gfx, $this->id);
		LogicInternalUser::addPoints(LogicSession::getUserId(), 20);		
	}
	
	/* pobiera ilosc stalych bywalcow */
	function getBywalcyAmount() {
		$res = mysql_query('SELECT COUNT(*) FROM bywalec WHERE bar='.$this->id);
		$row = mysql_fetch_row($res);
		return $row[0];
	}
	
	/* srednia ocena baru
		zwraca albo ocene, albo 0 jesli nie da sie sredniej policzyc */
	function avgMark() {
		$res = mysql_query('SELECT o0, o1, o2, o3, o4, o5, o6, o7, o8, o9, o10, o11, o12 FROM cache_bar_mark WHERE bar='.$this->id);
		if (mysql_num_rows($res) == 0) return 0;
		
		$row = mysql_fetch_row($res);
		
		$sum = 0;
		$count = 0;
		for ($i=0; $i<13; $i++) 
			if ($row[$i] != null) {
				$sum += $row[$i];
				$count++;
			}
		
		return round($sum/$count, 2);
	}
	
	/* pobiera id gfx pierwszego zdjecia baru lub null jesli takowe nie istnieje */
	function getFirstPhoto() {
		if ($this->photo == 0) return null;
				
		$res = mysql_query('SELECT gfx FROM barphoto WHERE id='.$this->photo);
		if (mysql_num_rows($res) == 0) die('Problem ze spojnoscia bazy danych - brak w bazie zdjecia baru, mimo ze w profilu baru istnieje!');
		$row = mysql_fetch_row($res);
		return $row[0];
	}
	
	/* sprawdza czy bar ma fote */
	function doHasPhoto() {
		return ($this->photo != 0);
	}
	
	/* pobierz ModelMark danego usera. $userid to id usera */
	function getModelMark($userid) {
        iof($userid);
		$mm = new ModelMark($this->id, $userid);
		return $mm;
	}
	
	/* pobiera tablice z przecietnymi ocenami baru. jest to generalnie tablica czternastoelementowa, po ocenie na kategorie. moze byc albo liczba albo null(brak oceny) */
	function getAverageMark() {
		$res = mysql_query('SELECT * FROM cache_bar_mark WHERE bar='.$this->id);
		if (mysql_num_rows($res) == 0) {
			$a = array(null, null, null, null,
					   null, null, null, null,
					   null, null, null, null,
					   null, null);
			return $a;
		} else 
		{
			$row = mysql_fetch_row($res);
			$ar = array();
			for ($i = 1; $i < 14; $i++) $ar[] = $row[$i];
			return $ar;
		}
	}
	
	/* pobiera liste wszystkich gfx id zdjec baru. pierwszy indeks to glowna fota */
	function getOrganizedPhotos() {
		$photos = $this->getPhotos();
		
		if (count($photos) == 0) return array();
		
		$mainphoto = $this->getFirstPhoto();
		
		$tl_list = array($mainphoto);
		foreach ($photos as $photo) 
			if ($photo != $mainphoto) $tl_list[] = $photo;
		return $tl_list;
	}
	
	/* pobiera liste gfx id zdjec baru */
	function getPhotos() {
		$res = mysql_query('SELECT gfx FROM barphoto WHERE bar='.$this->id);
		$phs = array();
		while ($row = mysql_fetch_row($res)) $phs[] = $row[0];
		return $phs;
	}
	
	/* zwraca liste ModelBarComment, ktore sa komentarzami baru, posortowanymi od najmlodszych do najstarszych */
	function getComments() {
		$res = mysql_query('SELECT id FROM bar_comment WHERE bar='.$this->id.' ORDER BY when_added DESC');
		$cms = array();
		while ($row = mysql_fetch_row($res)) $cms[] = new ModelBarComment($row[0]);
		return $cms;
	}
	
	/* user $uid komentuje bar komentarzem o tresci $content. zwraca id komenta */
	function comment($uid, $content) {
        iof($uid);
		LogicInternalAktualnosc::repost($uid, 1, $uid, $this->id, null);
	
		LogicInternalUser::addPoints($uid, 1);
	
		mysql_query('INSERT INTO bar_comment VALUES (NULL, '.$this->id.', "'.mres($uid).'", "'.mres($content).'", NOW())');
		return mysql_insert_id();
	}
	
	/* sprawdza czy user id $uid jest bywalcem */
	function isBywalec($uid) {
        iof($uid);
		$res = mysql_query('SELECT COUNT(*) FROM bywalec WHERE (bar='.$this->id.') AND (user='.$uid.')');
		$row = mysql_fetch_row($res);
		return ($row[0] > 0);
	}
	
	/* ustawia usera o id $uid jako bywalca */
	function setBywalec($uid) {
        iof($uid);
	
		LogicInternalAktualnosc::repost($uid, 0, $uid, $this->id, null);
	
		mysql_query('INSERT INTO bywalec VALUES (NULL, '.$uid.', '.$this->id.')');
	}
	
	/* usuwa usera $uid jako bywalca */
	function deleteBywalec($uid) {
		mysql_query('DELETE FROM bywalec WHERE (bar='.$this->id.') AND (user='.$uid.')');
	}
	
	/* pobiera liste bywalcow.
		jesli $max jest null to pobiera wszystkich
		jesli $max jest liczba to tyle max bywalcow dostaniesz
		zwraca tablice ktorej elementami sa id uzytkownikow
	   */
	function getBywalcy($max) {
		if ($max == null)
			$q = 'SELECT user FROM bywalec WHERE bar='.$this->id;
		else
			$q = 'SELECT user FROM bywalec WHERE bar='.$this->id.' ORDER BY RAND() LIMIT '.$max;
			
		$res = mysql_query($q);
		$byw = array();
		while ($row = mysql_fetch_row($res)) $byw[] = $row[0];
		return $byw;
	}
}

class ModelUser {
	/* id musi byc poprawne i istniejace, inaczej zachowanie niezdefiniowane */
	function __construct($id) {
		iof($id);
		$res = mysql_query("SELECT * FROM user WHERE id=".$id);
		$row = mysql_fetch_array($res);
		
		$this->id = $id;
		$this->name = $row['name'];
		$this->surname = $row['surname'];
		$this->login = $row['login'];
		$this->password = $row['password'];
		$this->city = $row['city'];
		$this->region = $row['region'];
		$this->email = $row['email'];
		$this->active = ($row['active'] == 1);
		$this->avatar = $row['avatar'];
		$this->gg = $row['gg'];
		$this->phone = $row['phone'];
		$this->pointsP = $row['pointsP'];
		$this->pointsC = $row['pointsC'];
		$this->points = $this->pointsP + $this->pointsC;	// legacy
		$this->when_added = strtotime($row['when_added']);
        $this->prize_id = $row['prize_id'];
	}
	
	/* ustawia imie */
	function setName($name) {
		$this->name = $name;
		mysql_query('UPDATE user SET name="'.mres($name).'" WHERE id='.$this->id);
	}

	/* ustawia nazwisko */
	function setSurname($name) {
		$this->surname = $name;
		mysql_query('UPDATE user SET surname="'.mres($name).'" WHERE id='.$this->id);
	}

	
	/* powoduje wyslanie maila z linkiem umozliwiajacym wygenerowanie nowego hasla */
	function regeneratePassword() {
		$password = "";
		$length = 8;	// 8 znakow hasla
		$possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";	// zestaw mozliwych znakow
		$maxlength = strlen($possible);
		if ($length > $maxlength) $length = $maxlength;
		$i = 0; 
		while ($i < $length) { 
		  $char = substr($possible, mt_rand(0, $maxlength-1), 1);
		  if (!strstr($password, $char)) { 
			$password .= $char;
			$i++;
		  }
		}	
			
		$this->changePassword($password);
		mail($this->email, 'Nowe hasło', "Twoje hasło do konta ".$this->login." zostało zmienione.\n\nOto Twoje nowe hasło: ".$password."\n\nMożesz je zmienić w każdej chwili w edycji swojego profilu.\n\nJeśli ta wiadomość Cię nie dotyczy to po prostu ją zignoruj.\n\nPozdrawiamy\nZespół Trybar", 'Content-Type: text/plain; charset="UTF-8"');
		return true;
	}

	/* zwraca haszkod aktywacji */
	function getActivateHashcode() {
		return sha1($this->login.$this->when_added);
	}
	
	/* aktywuje usera */
	function activate() {
		$this->active = true;
		mysql_query('UPDATE user SET active=1 WHERE id='.$this->id);
	}
	
	/* prosi innego usera o id $uid o uznanie za znajomego */
	function addZnajomy($uid) {
        iof($uid);
		if ($this->isZnajomy($uid)) die('Już znajomy lub już wysłano prośbę!');
		
		mysql_query('INSERT INTO znajomy VALUES (NULL, 0, '.$this->id.', '.$uid.')');
		$zna_id = mysql_insert_id();
		mysql_query('INSERT INTO messages VALUES (NULL, '.$this->id.', '.$uid.', "Prośba o potwierdzenie znajomości", "", 0, NOW(), '.$zna_id.')');
	}
	
	/* usuwa znajomego(potwierdzonego lub nie) $uid */
	function delZnajomy($uid) {
        iof($uid);
		$res = mysql_query('SELECT id FROM znajomy WHERE ((user1='.$this->id.') AND (user2='.$uid.')) OR ((user2='.$this->id.') AND (user1='.$uid.'))');
		
		if (mysql_num_rows($res) > 1) die('Błąd bazy danych - multirefleksywność relacji!');
		
		$row = mysql_fetch_row($res);
		$zna_id = $row[0];
	
	
		mysql_query('DELETE FROM znajomy WHERE id='.$zna_id);
		mysql_query('DELETE FROM messages WHERE functional='.$zna_id);
	}

	/* sprawdza czy $uid jest znajomym(potwierdzonym lub nie) */
	function isZnajomy($uid) {
        iof($uid);
		if ($uid == $this->id) die('uid takie samo jak ten uzytkownik!');
		$res = mysql_query('SELECT COUNT(*) FROM znajomy WHERE ((user1='.$this->id.') AND (user2='.$uid.')) OR ((user2='.$this->id.') AND (user1='.$uid.'))');
		$row = mysql_fetch_row($res);
		if ($row[0] > 1) die('Brak spójności w bazie danych!');
		return ($row[0] == 1);
	}
	
	/* sprawdza czy $uid jest znajomym potwierdzonym
	   nie uzywaj tego do sprawdzania duzych ilosci danych - do tego lepiej wez tablice z getFriends() */
	function isConfirmedZnajomy($uid) {
        iof($uid);
		if ($uid == $this->id) die('uid takie samo jak ten uzytkownik!');
		$res = mysql_query('SELECT COUNT(*) FROM znajomy WHERE (confirmed=1) AND (((user1='.$this->id.') AND (user2='.$uid.')) OR ((user2='.$this->id.') AND (user1='.$uid.')))');
		$row = mysql_fetch_row($res);
		if ($row[0] > 1) die('Brak spójności w bazie danych!');
		return ($row[0] == 1);
	}

	/* zwraca liste uzytkownikow ktorzy prosza tego usera o uznanie za znajomego
	   zwrotka jest lista identyfikatorow */
	function getZnajomyRequests() {
		$res = mysql_query('SELECT user2 FROM znajomy WHERE (user1='.$this->id.') AND (confirmed=0)');
		$prosby = array();
		while ($row = mysql_fetch_row($res)) $prosby[] = $row[0];
		return $prosby;
	}
	
	/* przyjmuje prosbe usera $uid o uznanie za znajomego
	   nic sie nie stanie jesli $uid juz jest jego znajomym lub nigdy go o to nie prosil */
	function acceptZnajomyRequest($uid) {
		iof($uid);	
		mysql_query('UPDATE znajomy SET confirmed=1 WHERE user2='.$uid);
		
		LogicInternalAktualnosc::repost($this->id, 6, $this->id, $uid);
		LogicInternalAktualnosc::repost($uid, 6, $uid, $this->id);
	}


	/* sprawdza czy $uid jest znajomym NIEpotwierdzonym */
	function isUnconfirmedZnajomy($uid) {
		iof($uid);
		if ($uid == $this->id) die('uid takie samo jak ten uzytkownik!');
		$res = mysql_query('SELECT COUNT(*) FROM znajomy WHERE (confirmed=0) AND (((user1='.$this->id.') AND (user2='.$uid.')) OR ((user2='.$this->id.') AND (user1='.$uid.')))');
		$row = mysql_fetch_row($res);
		if ($row[0] > 1) die('Brak spójności w bazie danych!');
		return ($row[0] == 1);
	}

	/* dodaje punkty */
	function addPoints($pts) {
        iof($pts);
		$this->pointsC += $pts;
		$this->points += $pts;		// legacy
		mysql_query('UPDATE user SET pointsC='.$this->pointsC.' WHERE id='.$this-id);
	}
	
	/* sprawdza czy pass jest haslem uzytkownika */
	function verifyPassword($pass) {
		$res = mysql_query('SELECT COUNT(id) FROM user WHERE (login="'.mres($this->login).'") AND (password="'.sha1($this->login.$pass).'")');
		$row = mysql_fetch_row($res);
		return ($row[0] > 0);		
	}
	
	/* zmienia haslo */
	function changePassword($pass) {
		mysql_query('UPDATE user SET password="'.sha1($this->login.$pass).'" WHERE id='.$this->id);
	}
	
	/* zmienia miasto */
	function changeCity($city) {
		mysql_query('UPDATE user SET city="'.mres($city).'" WHERE id='.$this->id);
	}
	
	/* zwraca liczbe znajomych */
	function Friends() {
		$res = mysql_query('SELECT COUNT(id) FROM znajomy WHERE ((user1='.$this->id.') OR (user2='.$this->id.')) AND (confirmed=1)');
		$row = mysql_fetch_row($res);
		return $row[0];
	}

	/* zwraca liczbe barow */
	function countBars() {
		$res = mysql_query('SELECT COUNT(id) FROM bar WHERE user='.$this->id);
		$row = mysql_fetch_row($res);
		return $row[0];
	}
	
	/* zwraca liczbe wiadomosci w skrzynce odbiorczej */
	function getMessages() {
		$res = mysql_query('SELECT COUNT(id) FROM messages WHERE receiver='.$this->id);
		$row = mysql_fetch_row($res);
		return $row[0];
	}

	/* zwraca liczbe wiadomosci w skrzynce odbiorczej - tylko nieodebrane*/
	function getUnreadedMessages() {
		$res = mysql_query('SELECT COUNT(id) FROM messages WHERE (receiver='.$this->id.') AND (readed=0)');
		$row = mysql_fetch_row($res);
		return $row[0];
	}
	
	/* zwraca pozycje w rankingu
		moze zwrocic null jesli na chwile obecna jest nie do ustalenia*/
	function rankingPosition() {
		$res = mysql_query("SELECT id FROM cache_ranking_user2 WHERE user=".$this->id);
		if (mysql_num_rows($res) == 0) return null;
		$row = mysql_fetch_row($res);
		return $row[0];
	}
	
	/* zwraca ilosc barow w ktorych user jest st. bywalcem */
	function getBywalecCount() {
		$res = mysql_query('SELECT COUNT(id) FROM bywalec WHERE user='.$this->id);
		$row = mysql_fetch_row($res);
		return $row[0];
	}
	
	/* pobiera tablice obiektow ModelUser reprezentujaca userow ktorzy sa znajomymi tego usera */
	function getFriends() {
		$friends = array();
		$res = mysql_query('SELECT user1 FROM znajomy WHERE (user2='.$this->id.') AND (confirmed=1)');
		while ($row = mysql_fetch_row($res)) $friends[] = new ModelUser($row[0]);
		$res = mysql_query('SELECT user2 FROM znajomy WHERE (user1='.$this->id.') AND (confirmed=1)');
		while ($row = mysql_fetch_row($res)) $friends[] = new ModelUser($row[0]);
		return $friends;
	}
	
	/* zwraca true jesli uzytkownik ma avatar, inaczej false */
	function hasAvatar() {
		return ($this->avatar);
	}
	
	/* dodaje uzytkownikowi avatar. $id to identyfikator gfx, ktory dostajesz z storeAs
	
		OSTROZNIE! Jesli uzytkownik ma poprzedni avatar, to zostanie on USUNIETY!!!
	*/
	function setAvatar($id) {
        iof($id);
		if ($this->avatar != 0) mysql_query('DELETE FROM gfx WHERE id='.$this->avatar);
	
		mysql_query('UPDATE user SET avatar='.$id.' WHERE id='.$this->id);
		$this->avatar = $id;
	}
	
	/* dodaje zdjecie uzytkownika 
		$id to id gfx obrazu, a description to opis zdjecia 
        zwraca false jesli nie dodano fotki */
	function addPhoto($id, $description) {
        iof($id);
        $res = mysql_query('SELECT COUNT(*) FROM userphoto WHERE user='.$this->id);
        $row = mysql_fetch_row($res);
        if ($row[0] > LIMIT_USER_PHOTOS) return false;
        
		mysql_query('INSERT INTO userphoto VALUES (NULL, '.$this->id.', '.$id.', "'.mres($description).'")');
	
		$id = mysql_insert_id();
		
		LogicInternalAktualnosc::repost($this->id, 5, $this->id, $id, 0);
		LogicInternalUser::addPoints($this->id, 5);
        return true;
	}
	
	/* pobiera wszystkie zdjecia usera. wynikiem jest lista, skladajaca sie z dwuelementowych list - id gfx i jego opisu 
			! DEPRECATED - nie zaleca sie wykorzystywania ! zamiast tego uzywaj getPhotos()
	*/
	function getPhotosSimple() {
		$res = mysql_query('SELECT gfx, description FROM userphoto WHERE user='.$this->id);
		$photos = array();
		while ($row = mysql_fetch_row($res)) {
			$photos[] = $row;
		}
		return $photos;
	}
	
	function getPhotosCount() {
		return count($this->getPhotos());
	}
	
	/* pobiera wszystkie zdjecia usera jako tablice ModelUserPhoto */
	function getPhotos() {
		$res = mysql_query('SELECT id FROM userphoto WHERE user='.$this->id);
		$photos = array();
		while ($row = mysql_fetch_row($res)) $photos[] = new ModelUserPhoto($row[0]);
		return $photos;		
	}
	
	/* sortuje wynikowa liste z getPhotos() tak aby zdjecie o identyfikatorze userphoto $id znalazlo sie na pierwszym miejscu.
		jesli $id nie bedzie pasowalo do niczego, lista wroci w niezdefiniowanej kolejnosci
		*/
	static function prioritySortPhotos($photos, $id) {
        iof($id);
		$p = array();
		
		foreach($photos as $photo) if ($photo->id == $id) $p[] = $photo;			
		foreach($photos as $photo) if ($photo->id != $id) $p[] = $photo;
		
		return $p;
	}
	
	/* wysyla wiadomosc do uzytkownika o id $id od biezacego uzytkownika */
	function sendMessage($id, $title, $content) {
		iof($id);	
		mysql_query('INSERT INTO messages VALUES (NULL, '.$this->id.', '.$id.', "'.mres($title).'", "'.mres($content).'", 0, NOW(), 0)');
        
        $target_user = new ModelUser($id);
        
		mail($target_user->email, 'Nowa wiadomość', " Na Twoim koncie w portalu Trybar pojawiła się nowa prywatna wiadomość.\nBy ją przeczytać zaloguj się na: http://www.trybar.pl\n\nPozdrawiamy\nZespół Trybar", 'Content-Type: text/plain; charset="UTF-8"');        
	}
	
	/* lista odebranych wiadomosci. zwraca liste ktorej elementami sa piecioelementowe listy
		id wiadomosc
		id usera od kogo
		tytul wiadomosci
		kiedy wyslano
		true / false czy przeczytana
		
		sa one posortowane od najnowszej do najstarszej
			! DEPRECATED - nie zaleca sie wykorzystywania ! zamiast tego uzywaj inbox()
		*/
	function inboxSimple() {
		$res = mysql_query('SELECT id, sender, title, when_sent, readed FROM messages WHERE receiver='.$this->id.' ORDER BY when_sent DESC');
		$msgs = array();
		while ($row = mysql_fetch_row($res)) {
			$msgs[] = array($row[0], $row[1], $row[2], strtotime($row[3]), ($row[4] == 1));
		}
		return $msgs;		
	}
	
	/* zwraca liste odebranych wiadomosci, po numerze strony. posortowane od najnowszej do najstarszej.
		zwraca liste ModelMessage
		strony $page_number numerujemy od 1*/
	function inbox($page_number) {
        iof($page_number);
		$page_number = (int)$page_number-1;
		$res = mysql_query('SELECT id FROM messages WHERE receiver='.$this->id.' ORDER BY when_sent DESC LIMIT '.$page_number*MESSAGES_PER_PAGE.', '.MESSAGES_PER_PAGE);
		$msgs = array();
		while ($row = mysql_fetch_row($res)) $msgs[] = new ModelMessage($row[0]);
		return $msgs;
	}
	
	/* pobiera liste barow ktore ten uzytkownik dodal 
	  zwraca liste obiektow ModelBar */
	function getAddedBars() {
		$res = mysql_query('SELECT id FROM bar WHERE user='.$this->id);
		$bary = array();
		while ($row = mysql_fetch_row($res)) $bary[] = new ModelBar($row[0]);
		return $bary;
	}
	
	/* pobiera $x ostatnich aktualnosci, posortowanych od najmlodszego do najstarszego. wynikiem jest lista ModelAktualnosc */
	function getAktualnosci($x) {
        iof($x);
		$res = mysql_query('SELECT id FROM aktualnosci WHERE user='.$this->id.' ORDER BY when_added DESC LIMIT '.$x);
		$akt = array();
		while ($row = mysql_fetch_row($res)) $akt[] = new ModelAktualnosc($row[0]);
		return $akt;
	}
	
	/* zwraca liste ModelBar w ktorej user jest st. bywalcem */
	function getBaryBywalec() {
		$res = mysql_query('SELECT bar FROM bywalec WHERE user='.$this->id);
		$bary = array();
		while ($row = mysql_fetch_row($res)) $bary[] = new ModelBar($row[0]);
		return $bary;
	}
}

class LogicBars {

    /* pobiera bar z nazwy. zwraca null jesli nie znalazl, w innym przypadku zwraca ModelBar */
    static function getBarByName($name) {
        $res = mysql_query('SELECT id FROM bar WHERE name="'.mres($name).'"');
        if (mysql_num_rows($res) == 0) return null;
        
        $row = mysql_fetch_row($res);
        $x = new ModelBar($row[0]);
        return $x;
    }

	/* zwraca liste identyfikatorow barow jak leci w rankingu, po numerze strony. posortowane od najwyzszego do najnizszczego
		zwraca liste id barow
		strony $page_number numerujemy od 1*/
	static function ranking($page_number) {
		iof($page_number);	
		$page_number = (int)$page_number-1;
		$res = mysql_query('SELECT bar FROM cache_ranking_bar ORDER BY position ASC LIMIT '.$page_number*MAX_RANKING_PER_PAGE.', '.MAX_RANKING_PER_PAGE);
		$msgs = array();
		while ($row = mysql_fetch_row($res)) $msgs[] = $row[0];
		return $msgs;
	}

	/* zwraca ilosc barow w rankingu */
	static function rankingBarCount() {
		$res = mysql_query('SELECT COUNT(*) FROM cache_ranking_bar');
		$row = mysql_fetch_row($res);
		return $row[0];
	}

	/* dodaje nowy bar.
	   zwraca ID nowego baru
	   $gfx_photo to identyfikator GFX zaladowanego zdjecia - jesli brak to daj 0 albo null
	   $who_adds_id to ID usera ktory dodaje bar */
	static function create($name, $street, $city, $region, $description, $gfx_photo, $who_adds_id) {
		mysql_query('INSERT INTO bar VALUES (NULL, "'.mres($name).'",'.
												  '"'.mres($street).'", '.
												  '"'.mres($city).'", '.
												  '"'.mres($region).'", '.
												  '"'.mres($description).'", '.
												  'NOW(), '.$who_adds_id.', 0)');
		$id = mysql_insert_id();
		
		LogicInternalUser::addPoints($who_adds_id, 10);		
		
		if ($gfx_photo) {
			mysql_query('INSERT INTO barphoto VALUES (NULL, '.$id.', '.$gfx_photo.')');
			$bpid = mysql_insert_id();
			mysql_query('UPDATE bar SET photo='.$bpid.' WHERE id='.$id);
			LogicInternalUser::addPoints($who_adds_id, 20);		
		}

		$gfx_photo = ($gfx_photo ? $gfx_photo : 0);

		LogicInternalAktualnosc::repost($who_adds_id, 3, $who_adds_id, $id, $gfx_photo);
				
		return $id;
	}
	
	/* zwraca liste top X barow, posortowana od najwyzszej pozycji do najnizszej.
        zwrotka jest tablica z ID barów */
	static function topX($x) {
        iof($x);
		$res = mysql_query('SELECT bar FROM cache_ranking_bar ORDER BY position ASC LIMIT '.$x);
		$bars = array();
		while ($row = mysql_fetch_row($res)) $bars[] = $row[0];
		return $bars;
	}
	
	/* szuka barow. Zwraca liste ModelBar */
	static function search($wojew, $miasto) {
		$res = mysql_query('SELECT id FROM bar WHERE (region = "'.mres($wojew).'") AND (city LIKE "%'.mres($miasto).'%") ORDER BY name ASC');
		$bary = array();
		while ($row = mysql_fetch_row($res)) $bary[] = new ModelBar($row[0]);
		return $bary;
	}
	
	/* sprawdza istnienie danego baru po Id */
	static function barExistsId($id) {
		iof($id);	
		$res = mysql_query('SELECT COUNT(id) FROM bar WHERE id="'.mres($id).'"');
		$row = mysql_fetch_row($res);
		return ($row[0] > 0);	
	}
}


class LogicSession {
	/* zwraca czy user jest zalogowany */
	static function isLoggedIn() {
		return isset($_SESSION['user.id']);
	}
	/* zwraca czy moze edytowac profil uzytkownika o ID x */
	static function can_edit_profile($x) { return $_SESSION['user.id'] == $x; }
	/* zwraca czy moze edytowac bar o ID x */
	static function can_edit_bar($x) { 
        iof($x);
		$uid = $_SESSION['user.id'];
		$res = mysql_query('SELECT COUNT(*) FROM bar WHERE (id='.$x.') AND (user='.$uid.')');
		$row = mysql_fetch_row($res);
		return ($row[0] == 1);
	}
	
	/* inicjuje sesje z zabezpieczeniami wlacznie */
	static function start() {
		@session_start();

		// wykryj problemy z sesja
		$has_problems = false;
			// zgodnosc user-agent
		if (!isset($_SESSION['useragent'])) $_SESSION['useragent'] = $_SERVER['HTTP_USER_AGENT'];
		else $has_problems = $has_problems or ($_SESSION['useragent'] != $_SERVER['HTTP_USER_AGENT']);
		
		if ($has_problems) $_SESSION = array();	
	}	
	/* wylogowuje usera */
	static function logout() {
		unset($_SESSION['user.id']);
		$_SESSION = array();
		session_destroy();
	}
	/* wywoluj tylko jesli zalogowany - pobiera ModelUser aktualnie zalogowanego usera */
	static function getUser() {
		$usr = new ModelUser($_SESSION['user.id']);
		return $usr;
	}
	/* zwraca ID aktualnie zalogowanego usera - musi byc zalogowany! */
	static function getUserId() {
		return $_SESSION['user.id'];
	}
	/* loguje usera. zwraca true jesli poprawnie zalogowany, false jesli niepoprawne user/haslo lub user nieaktywny */
	static function login($login, $password) {
		$res = mysql_query('SELECT id FROM user WHERE (login="'.mres($login).'") AND (password="'.sha1($login.$password).'") AND (active=1)');
		if (mysql_num_rows($res) == 0) return false;
		
		$row = mysql_fetch_row($res);
		$_SESSION['user.id'] = $row[0];
		return true;
	}		
}

class LogicUser {
	/* pobiera ModelUser po loginie - user musi istniec! */
	static function getUserByLogin($login) {
		$res = mysql_query('SELECT id FROM user WHERE login="'.mres($login).'"');
		$row = mysql_fetch_row($res);
		$mu = new ModelUser($row[0]);
		return $mu;		
	}


	/* zwraca czy user o danej nazwie istnieje, zwraca boolean */
	static function userExistsLogin($login) {
		$res = mysql_query('SELECT COUNT(*) FROM user WHERE login="'.mres($login).'"');
		$row = mysql_fetch_row($res);
		return ($row[0] > 0);
	}
	/* zwraca czy user o danej nazwie istnieje, zwraca boolean */
	static function userExistsId($id) {
		iof($id);
		$res = mysql_query('SELECT COUNT(id) FROM user WHERE id="'.mres($id).'"');
		$row = mysql_fetch_row($res);
		return ($row[0] > 0);
	}
	/* zwraca czy user o danym mailu istnieje, zwraca boolean */
	static function userExistsEmail($email) {
		$res = mysql_query('SELECT COUNT(id) FROM user WHERE email="'.mres($email).'"');
		$row = mysql_fetch_row($res);
		return ($row[0] > 0);
	}
	/* zwraca czy user o danym loginie jest aktywny (tzn. potwierdzil maila) */
	static function userActive($login) {
		$res = mysql_query('SELECT active FROM user WHERE login="'.mres($login).'"');
		$row = mysql_fetch_row($res);
		return ($row[0] == 1);
	}
	/* rejestruje nowego usera - wymagane jest wszystko to co lay mówi że wymagane
	   login i email musza byc juz sprawdzone ze unikatowe!
	   zwraca Id nowego usera */	
	static function registerUser($name, $surname, $login, $password, $city, $region, $email, $gg, $phone) {
		mysql_query('INSERT INTO user VALUES (NULL, "'.mres($name).'", '.
												   '"'.mres($surname).'", '.
												   '"'.mres($login).'", '.
												   '"'.sha1($login.$password).'", '.
												   '"'.mres($city).'", '.
												   '"'.mres($region).'", '.
												   '"'.mres($email).'", '.
												   '"'.mres($gg).'", '.
												   '"'.mres($phone).'", '.
												   'NULL, 0, 0, NOW(), 0, NOW(), 0)');
		$id = mysql_insert_id();
		$user = new ModelUser($id);
		
		$haszkod = $user->getActivateHashcode();
		
		mail($user->email, 'Aktywacja w Trybar', "Twoje konto ".$user->login." zostało pomyślnie zarejestrowane na portalu Trybar. By je aktywować kliknij w poniższy link.\n\nhttp://www.trybar.pl/aktywacja.php?login=".urlencode($login)."&haszkod=".$haszkod."\n\nNastępnie możesz się już zalogować.\n\nJeśli ta wiadomość Cię nie dotyczy to po prostu ją zignoruj.\n\nPozdrawiamy\nZespół Trybar", 'Content-Type: text/plain; charset="UTF-8"');
											   
		return mysql_insert_id();
	}
	
	/* aktywuje usera o istniejacym loginie. zwraca true jesli sie udalo, false jesli nie */
	static function activate($login, $haszkod) {
		$usr = LogicUser::getUserByLogin($login);
		if ($haszkod != $usr->getActivateHashcode()) return false;
		if ($usr->active) return false;
		$usr->activate();
		return true;
	}
	
	/* zwraca liste identyfikatorow użytkowników jak leci w rankingu, po numerze strony. posortowane od najwyzszego do najnizszczego
		zwraca liste id userów
		strony $page_number numerujemy od 1*/
	static function ranking($page_number) {
        iof($id);
		$page_number = (int)($page_number-1);
		$res = mysql_query('SELECT user FROM cache_ranking_user2 ORDER BY id ASC LIMIT '.$page_number*MAX_RANKING_PER_PAGE.', '.MAX_RANKING_PER_PAGE);
		$msgs = array();
		while ($row = mysql_fetch_row($res)) $msgs[] = $row[0];
		return $msgs;
	}
	
	/* zwraca ilosc userow w rankingu */
	static function rankingUserCount() {
		$res = mysql_query('SELECT COUNT(*) FROM cache_ranking_user2');
		$row = mysql_fetch_row($res);
		return $row[0];
	}
	
}


class ModelNewsComment {
	/* id musi byc poprawne i istniejace, inaczej zachowanie niezdefiniowane */
	function __construct($id) {
        iof($id);
		$res = mysql_query("SELECT * FROM news_comment WHERE id=".$id);
		$row = mysql_fetch_array($res);
		$this->id = $id;
		$this->news = $row['news'];
		$this->user = $row['user'];
		$this->content = $row['content'];
		$this->when_added = strtotime($row['when_added']);
	}
	/* pobiera ModelUser usera ktory dodal */
	function getUser() {
		$u = new ModelUser($this->user);
		return $u;
	}
	
	function delete() {
		mysql_query('DELETE FROM news_comment WHERE id='.$this->id);
	}
}

class ModelNews {
	/* id musi byc poprawne i istniejace, inaczej zachowanie niezdefiniowane */
	function __construct($id) {
        iof($id);
		$res = mysql_query("SELECT * FROM news WHERE id=".$id);
		$row = mysql_fetch_array($res);
		$this->id = $id;
		$this->title = $row['title'];
		$this->content = $row['content'];
		$this->when_added = strtotime($row['when_added']);
	}
	/* pobiera komentarze w kolejnosci chronologicznej */
	function getComments() {
		$res = mysql_query("SELECT id FROM news_comment WHERE news=".$this->id." ORDER BY when_added DESC");
		$comments = array();
		while ($row = mysql_fetch_row($res)) $comments[] = new ModelNewsComment($row[0]);
		return $comments;
	}
	
	/* dodaje komentarz
	   $who to model usera reprezentujacy tego kto pisze. Najczesciej podaj to jako LogicSession::getUser()
	   $content to tresc komentarza. Nie zapomnij o zabezpieczeniu html_entities zeby ktos nie pisal kodu HTML !
	   zwraca Id dodanego komentarza */
	function comment($who, $content) {
		$cnt = mres($content);
		mysql_query('INSERT INTO news_comment VALUES (NULL, '.$this->id.', '.$who->id.', "'.$cnt.'", NOW())');
		return mysql_insert_id();
	}
}

class LogicNews {
	/* pobiera X najnowszych newsów
	   zwraca tablice z X obiektami ModelNews */
	static function getLatestX($x) {
		$res = mysql_query('SELECT id FROM news ORDER BY when_added DESC LIMIT 0, '.$x);
		$items = array();
		while($row = mysql_fetch_row($res)) {
			$items[] = new ModelNews($row[0]);
		}
		return $items;
	}
	
	/* sprawdza istnienie danego newsa po Id */
	static function newsExistsId($id) {
        iof($id);
		$res = mysql_query('SELECT COUNT(id) FROM news WHERE id="'.mres($id).'"');
		$row = mysql_fetch_row($res);
		return ($row[0] > 0);	
	}
	
	/* pobiera liste gfx do latajacego holendra na stronie glownej. 10 losowych fot baru, albo mniej */
	static function getPicturesMainpage() {
		$res = mysql_query('SELECT gfx FROM barphoto ORDER BY RAND() LIMIT 10');
		$gfxs = array();
		while ($row = mysql_fetch_row($res)) $gfxs[] = $row[0];
		return $gfxs;
	}
	
	/* pobiera liste do latajacego holendra na stronie glownej. 10 losowych fot baru, albo mniej
		zwrotka to lista max 10 lub mniej elementow, ktore sa tablicami. 1 indeks to id gfx foty, drugi to id baru*/
	static function getPicturesMainpageEx() {
		$res = mysql_query('SELECT gfx, bar FROM barphoto ORDER BY RAND() LIMIT 10');
		$gfxs = array();
		while ($row = mysql_fetch_row($res)) $gfxs[] = $row;
		return $gfxs;
	}

}


class LogicComments {
	/* zwraca czy istnieje komentarz foty usera o $id */
	static function existsUserPhotoComment($id) {
		iof($id);
		$res = mysql_query('SELECT COUNT(*) FROM userphoto_comment WHERE id='.$id);
		$row = mysql_fetch_row($res);
		return ($row[0] > 0);
	}

	/* zwraca czy istnieje komentarz news o $id */
	static function existsNewsComment($id) {
		iof($id);
		$res = mysql_query('SELECT COUNT(*) FROM news_comment WHERE id='.$id);
		$row = mysql_fetch_row($res);
		return ($row[0] > 0);
	}

	/* zwraca czy istnieje komentarz baru o $id */
	static function existsBarComment($id) {
		iof($id);
		$res = mysql_query('SELECT COUNT(*) FROM bar_comment WHERE id='.$id);
		$row = mysql_fetch_row($res);
		return ($row[0] > 0);
	}

}

class LogicHumanize {
	/* zwraca wyrazenie okreslajace czas w jezyku polskim. X to PHP-powy timestamp (czas).
	   wynikiem jest string postaci np. '4 miesiace temu' */
	static function ago($x) {
	
		$ago = time() - $x;
		
		if ($ago < 60) return 'Mniej niż minutę temu';
		if ($ago < 120) return 'Minutę temu';
		
		for ($i=2;$i<60;$i++) if ($ago < (($i+1)*60)) return $i.' minuty temu';

		for ($i=2;$i<24;$i++) if ($ago < (($i+1)*3600)) return $i.' godzin temu';
		
		if ($ago < 129600) return 'Wczoraj';
		if ($ago < 172800) return 'Półtorej dnia temu';
	
		if ($ago < 345600) return 'Trzy dni temu';
	
		return 'Dawno temu';	// zaślepka
	}
}
