<?php
function explorerDir($path){
	$folder = opendir($path);
	while($entree = readdir($folder)){		
		if($entree != "." && $entree != ".."){
			if(is_dir($path."/".$entree)){
				$sav_path = $path;
				$path .= "/".$entree;
				explorerDir($path);
				$path = $sav_path;
			}
			else {
				$path_source = $path."/".$entree;	
				$pathInfo = pathinfo( $path_source);
					$name = $pathInfo['filename'];
                    $tab[] = $entree;
			}
		}
	}
	closedir($folder);
    // var_dump($tab);
    return $tab; 

}



function indexer($name, $path){
    $pathComplet=$path.$name;

	$tabOccurence = compter($pathComplet);

    if(empty($tabOccurence)){
    }
    else{
        $langue = $tabOccurence['Langue'];
        echo 'La langue du document (FR/UK/Autre) : ';
        echo $langue;
        echo '<br/>';


        $nbMots = $tabOccurence['NbMot'];
        echo 'Le nombre de mots du document : ';
        echo $nbMots;
        echo '<br/>';


        $insertSource =  insertSource($langue, $nbMots, $name);

        foreach ($tabOccurence['mot'] as $mot => $frequence){
            $insertIndexation= insertIndexation($mot, $frequence, $name);
            if ($insertIndexation=== false) {
                throw new Exception('Pas possible');
            }
            else {
            }
        }
    echo'L indexation s est bien déroulée<br/>';
	}
}


function compter($source){
	$texte = file_get_contents ($source);
	$separateurs =  "’. ,…][(«»)\t\r\n" ;
	$tab_toks = lire($texte, $separateurs);

    $tab_occurrences['mot'] = array_count_values ($tab_toks['mot']);
    $tab_occurrences['NbMot'] = $tab_toks['NbMot'];
    $tab_occurrences['Langue'] = array_count_values ($tab_toks['Langue']);

    $choixlangue = 'Autre';
    $occurencelangue = 0;
    foreach ($tab_occurrences['Langue'] as $langue => $frequence){
        if ($frequence > $occurencelangue) {
        $choixlangue = $langue;
        $occurencelangue = $frequence;
        }
    }
    $tab_occurrences['Langue'] = $choixlangue;
    // var_dump($tab_occurrences);
    return $tab_occurrences; 
} 


function lire($texte, $separateurs){
	$tok =  strtok($texte, $separateurs);

    $motVideFR = array("a","à","â","abord","afin","ah","ai","aie","ainsi","allaient","allo","allô","allons","après","assez","attendu","au","aucun","aucune","aujourd","aujourd'hui","auquel","aura","auront","aussi","autre","autres","aux","auxquelles","auxquels","avaient","avais","avait","avant","avec","avoir","ayant","b","bah","beaucoup","bien","bigre","boum","bravo","brrr","c","ça","car","ce","ceci","cela","celle","celle-ci","celle-là","celles","celles-ci","celles-là","celui","celui-ci","celui-là","cent","cependant","certain","certaine","certaines","certains","certes","ces","cet","cette","ceux","ceux-ci","ceux-là","chacun","chaque","cher","chère","chères","chers","chez","chiche","chut","ci","cinq","cinquantaine","cinquante","cinquantième","cinquième","clac","clic","combien","comme","comment","compris","concernant","contre","couic","crac","d","da","dans","de","debout","dedans","dehors","delà","depuis","derrière","des","dès","désormais","desquelles","desquels","dessous","dessus","deux","deuxième","deuxièmement","devant","devers","devra","différent","différente","différentes","différents","dire","divers","diverse","diverses","dix","dix-huit","dixième","dix-neuf","dix-sept","doit","doivent","donc","dont","douze","douzième","dring","du","duquel","durant","e","effet","eh","elle","elle-même","elles","elles-mêmes","en","encore","entre","envers","environ","es","ès","est","et","etant","étaient","étais","était","étant","etc","été","etre","être","eu","euh","eux","eux-mêmes","excepté","f","façon","fais","faisaient","faisant","fait","feront","fi","flac","floc","font","g","gens","h","ha","hé","hein","hélas","hem","hep","hi","ho","holà","hop","hormis","hors","hou","houp","hue","hui","huit","huitième","hum","hurrah","i","il","ils","importe","j","je","jusqu","jusque","k","l","la","là","laquelle","las","le","lequel","les","lès","lesquelles","lesquels","leur","leurs","longtemps","lorsque","lui","lui-même","m","ma","maint","mais","malgré","me","même","mêmes","merci","mes","mien","mienne","miennes","miens","mille","mince","moi","moi-même","moins","mon","moyennant","n","na","ne","néanmoins","neuf","neuvième","ni","nombreuses","nombreux","non","nos","notre","nôtre","nôtres","nous","nous-mêmes","nul","o","o|","ô","oh","ohé","olé","ollé","on","ont","onze","onzième","ore","ou","où","ouf","ouias","oust","ouste","outre","p","paf","pan","par","parmi","partant","particulier","particulière","particulièrement","pas","passé","pendant","personne","peu","peut","peuvent","peux","pff","pfft","pfut","pif","plein","plouf","plus","plusieurs","plutôt","pouah","pour","pourquoi","premier","première","premièrement","près","proche","psitt","puisque","q","qu","quand","quant","quanta","quant-à-soi","quarante","quatorze","quatre","quatre-vingt","quatrième","quatrièmement","que","quel","quelconque","quelle","quelles","quelque","quelques","quelqu'un","quels","qui","quiconque","quinze","quoi","quoique","r","revoici","revoilà","rien","s","sa","sacrebleu","sans","sapristi","sauf","se","seize","selon","sept","septième","sera","seront","ses","si","sien","sienne","siennes","siens","sinon","six","sixième","soi","soi-même","soit","soixante","son","sont","sous","stop","suis","suivant","sur","surtout","t","ta","tac","tant","te","té","tel","telle","tellement","telles","tels","tenant","tes","tic","tien","tienne","tiennes","tiens","toc","toi","toi-même","ton","touchant","toujours","tous","tout","toute","toutes","treize","trente","très","trois","troisième","troisièmement","trop","tsoin","tsouin","tu","u","un","une","unes","uns","v","va","vais","vas","vé","vers","via","vif","vifs","vingt","vivat","vive","vives","vlan","voici","voilà","vont","vos","votre","vôtre","vôtres","vous","vous-mêmes","vu","w","x","y","z","zut","alors","aucuns","bon","devrait","dos","droite","début","essai","faites","fois","force","haut","ici","juste","maintenant","mine","mot","nommés","nouveaux","parce","parole","personnes","pièce","plupart","seulement","soyez","sujet","tandis","valeur","voie","voient","état","étions");
    $motVideUK = array("able","about","above","abroad","according","accordingly","across","actually","adj","after","afterwards","again","against","ago","ahead","ain't","all","allow","allows","almost","alone","along","alongside","already","also","although","always","am","amid","amidst","among","amongst","an","and","another","any","anybody","anyhow","anyone","anything","anyway","anyways","anywhere","apart","appear","appreciate","appropriate","are","aren't","around","as","a's","aside","ask","asking","associated","at","available","away","awfully","back","backward","backwards","be","became","because","become","becomes","becoming","been","before","beforehand","begin","behind","being","believe","below","beside","besides","best","better","between","beyond","both","brief","but","by","came","can","cannot","cant","can't","caption","cause","causes","certain","certainly","changes","clearly","c'mon","co","co.","com","come","comes","concerning","consequently","consider","considering","contain","containing","contains","corresponding","could","couldn't","course","c's","currently","dare","daren't","definitely","described","despite","did","didn't","different","directly","do","does","doesn't","doing","done","don't","down","downwards","during","each","edu","eg","eight","eighty","either","else","elsewhere","end","ending","enough","entirely","especially","et","etc","even","ever","evermore","every","everybody","everyone","everything","everywhere","ex","exactly","example","except","fairly","far","farther","few","fewer","fifth","first","five","followed","following","follows","for","forever","former","formerly","forth","forward","found","four","from","further","furthermore","get","gets","getting","given","gives","go","goes","going","gone","got","gotten","greetings","had","hadn't","half","happens","hardly","has","hasn't","have","haven't","having","he","he'd","he'll","hello","help","hence","her","here","hereafter","hereby","herein","here's","hereupon","hers","herself","he's","hi","him","himself","his","hither","hopefully","how","howbeit","however","hundred","i'd","ie","if","ignored","i'll","i'm","immediate","in","inasmuch","inc","inc.","indeed","indicate","indicated","indicates","inner","inside","insofar","instead","into","inward","is","isn't","it","it'd","it'll","its","it's","itself","i've","just","k","keep","keeps","kept","know","known","knows","last","lately","later","latter","latterly","least","less","lest","let","let's","like","liked","likely","likewise","little","look","looking","looks","low","lower","ltd","made","mainly","make","makes","many","may","maybe","mayn't","me","mean","meantime","meanwhile","merely","might","mightn't","mine","minus","miss","more","moreover","most","mostly","mr","mrs","much","must","mustn't","my","myself","name","namely","nd","near","nearly","necessary","need","needn't","needs","neither","never","neverf","neverless","nevertheless","new","next","nine","ninety","no","nobody","non","none","nonetheless","noone","no-one","nor","normally","not","nothing","notwithstanding","novel","now","nowhere","obviously","of","off","often","oh","ok","okay","old","on","once","one","ones","one's","only","onto","opposite","or","other","others","otherwise","ought","oughtn't","our","ours","ourselves","out","outside","over","overall","own","particular","particularly","past","per","perhaps","placed","please","plus","possible","presumably","probably","provided","provides","que","quite","qv","rather","rd","re","really","reasonably","recent","recently","regarding","regardless","regards","relatively","respectively","right","round","said","same","saw","say","saying","says","second","secondly","see","seeing","seem","seemed","seeming","seems","seen","self","selves","sensible","sent","serious","seriously","seven","several","shall","shan't","she","she'd","she'll","she's","should","shouldn't","since","six","so","some","somebody","someday","somehow","someone","something","sometime","sometimes","somewhat","somewhere","soon","sorry","specified","specify","specifying","still","sub","such","sup","sure","take","taken","taking","tell","tends","th","than","thank","thanks","thanx","that","that'll","thats","that's","that've","the","their","theirs","them","themselves","then","thence","there","thereafter","thereby","there'd","therefore","therein","there'll","there're","theres","there's","thereupon","there've","these","they","they'd","they'll","they're","they've","thing","things","think","third","thirty","this","thorough","thoroughly","those","though","three","through","throughout","thru","thus","till","to","together","too","took","toward","towards","tried","tries","truly","try","trying","t's","twice","two","un","under","underneath","undoing","unfortunately","unless","unlike","unlikely","until","unto","up","upon","upwards","us","use","used","useful","uses","using","usually","v","value","various","versus","very","via","viz","vs","want","wants","was","wasn't","way","we","we'd","welcome","well","we'll","went","were","we're","weren't","we've","what","whatever","what'll","what's","what've","when","whence","whenever","where","whereafter","whereas","whereby","wherein","where's","whereupon","wherever","whether","which","whichever","while","whilst","whither","who","who'd","whoever","whole","who'll","whom","whomever","who's","whose","why","will","willing","wish","with","within","without","wonder","won't","would","wouldn't","yes","yet","you","you'd","you'll","your","you're","yours","yourself","yourselves","you've","zero","a","how's","i","when's","why's","b","c","d","e","f","g","h","j","l","m","n","o","p","q","r","s","t","u","uucp","w","x","y","z","I","www","amount","bill","bottom","call","computer","con","couldnt","cry","de","describe","detail","due","eleven","empty","fifteen","fifty","fill","find","fire","forty","front","full","give","hasnt","herse","himse","interest","itse”","mill","move","myse”","part","put","show","side","sincere","sixty","system","ten","thick","thin","top","twelve","twenty","abst","accordance","act","added","adopted","affected","affecting","affects","ah","announce","anymore","apparently","approximately","aren","arent","arise","auth","beginning","beginnings","begins","biol","briefly","ca","date","ed","effect","et-al","ff","fix","gave","giving","heres","hes","hid","home","id","im","immediately","importance","important","index","information","invention","itd","keys","kg","km","largely","lets","line","'ll","means","mg","million","ml","mug","na","nay","necessarily","nos","noted","obtain","obtained","omitted","ord","owing","page","pages","poorly","possibly","potentially","pp","predominantly","present","previously","primarily","promptly","proud","quickly","ran","readily","ref","refs","related","research","resulted","resulting","results","run","sec","section","shed","shes","showed","shown","showns","shows","significant","significantly","similar","similarly","slightly","somethan","specifically","state","states","stop","strongly","substantially","successfully","sufficiently","suggest","thered","thereof","therere","thereto","theyd","theyre","thou","thoughh","thousand","throug","til","tip","ts","ups","usefully","usefulness","'ve","vol","vols","wed","whats","wheres","whim","whod","whos","widely","words","world","youd","youre");

    $i = 0;
    $j = 0;
    $nbMots = 0;
    $langue = false;


	while ($tok !== false) {
		$tok = strtok($separateurs);
		// $tok = strtolower($tok);

		if(strlen($tok) > 2) {
            if (in_array(strtolower($tok), $motVideFR)) {
                $langue = true;
                $tab_tok['Langue'][$i] = 'FR';
                $i++;

            } elseif (in_array(strtolower($tok), $motVideUK)) {
                $langue = true;
                $tab_tok['Langue'][$i] = 'UK';
                $i++;

            } else {
                $tab_tok['mot'][$j] = strtolower($tok);
                $j++;
                $nbMots++;
            }
 
        }

	}

    $tab_tok['NbMot']= $nbMots;

    if ($langue) {
        // echo 'Langue identifiée';
    }
    else{
        // echo 'Langue NON identifiée';
        $tab_tok['Langue'][$i] = 'Autre';
    }

    // var_dump($tab_tok);
	return $tab_tok;
}



function getSource($texte){
    $db = dbConnect();
    $result = $db->prepare('SELECT * FROM source WHERE source = ?');
    $result->execute(array($texte));
    while ($row = $result->fetch()){
        $infoSource[] = $row;
    }
    // var_dump($infoSource);
    return $infoSource;
}


function getIndexation($texte){
    $db = dbConnect();
    $result = $db->prepare('SELECT mot, occurence FROM indexation WHERE source = ? ORDER BY occurence DESC LIMIT 5');
    $result->execute(array($texte));
    while ($row = $result->fetch()){
        $infoIndexation[] = $row;
    }
    // var_dump($infoIndexation);
    return $infoIndexation;
}




function insertIndexation($mot, $occurence, $source){
    $db = dbConnect();
    $insert = $db->prepare('INSERT INTO indexation (mot, occurence, source) VALUES(?, ?, ?)');
    $affectedLines = $insert->execute(array($mot, $occurence, $source));
    return $affectedLines;
}

function insertSource($langue, $nbMots, $source){
    $db = dbConnect();
    $insert = $db->prepare('INSERT INTO source (langue, nbMots, source) VALUES(?, ?, ?)');
    $affectedLines = $insert->execute(array($langue, $nbMots, $source));
    return $affectedLines;
}


function insertLienSemantique($sourceA, $sourceB, $motsCommuns){
    $db = dbConnect();
    $insert = $db->prepare('INSERT INTO lienSemantique (sourceA, sourceB, motsCommuns) VALUES(?, ?, ?)');
    $affectedLines = $insert->execute(array($sourceA, $sourceB, $motsCommuns));
    echo 'Insertion des liens sémantiques dans la base de donnée faite';
    return $affectedLines;
}


// Connection db + Try & catch
function dbConnect(){
    try{
	    $db = new PDO('mysql:host=localhost;dbname=projetInnovant;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        // echo "Connection db réussie <br/>";
        return $db;
	}
	catch (Exception $e){
	    die('Erreur connection db : ' . $e->getMessage());
	}
}




















	

