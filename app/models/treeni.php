<?php

class Treeni extends BaseModel {

    public $id, $name, $kesto, $soveltuvuus, $kuvaus;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_string_length');
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Treeni');
        $query->execute();
        $rows = $query->fetchAll();
        $treenit = array();

        foreach ($rows as $row) {
            $treenit[] = new Treeni(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'kesto' => $row['kesto'],
                'soveltuvuus' => $row['soveltuvuus'],
                'kuvaus' => $row['kuvaus']
            ));
        }
        return $treenit;
    }

    public static function findId($id) {
        $query = DB::connection()->prepare('SELECT * FROM Treeni WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $treeni = new Treeni(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'kesto' => $row['kesto'],
                'soveltuvuus' => $row['soveltuvuus'],
                'kuvaus' => $row['kuvaus']
            ));
            return $treeni;
        }
        return null;
    }
    
    public function validate_name() {
        if (parent::validate_string_length($this->name, 2, 50) == false) {
            return 'Nimi tulee olla 2-50 kirjainta pitkä)';
        }
    }
    
    public static function getName() {
        $query = DB::connection()->prepare('SELECT * FROM Treeni ORDER BY random() LIMIT 1');
        $query->execute();
        $row = $query->fetch();
        if ($row) {
            $name = new Treeni(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'kesto' => $row['kesto'],
                'soveltuvuus' => $row['soveltuvuus'],
                'kuvaus' => $row['kuvaus']
            ));
            return $row['name'];
        }
        return null;
    }
    

    public static function findName($name) {
        $name = '%' . strtolower($name) . '%';
        $query = DB::connection()->prepare('SELECT * FROM Treeni WHERE LOWER(name) LIKE :name');
        $query->execute(array('name' => $name));
        $rows = $query->fetchAll();
        $treenit = array();
        if ($rows == null) {
            return NULL;
        }
        
        foreach ($rows as $row) {
            $treenit[] = new Treeni(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'kesto' => $row['kesto'],
                'soveltuvuus' => $row['soveltuvuus'],
                'kuvaus' => $row['kuvaus']
            ));
            
        }
        return $treenit;
    }

    public function save() {
        // Lisätään RETURNING id tietokantakyselymme loppuun, niin saamme lisätyn rivin id-sarakkeen arvon
        $query = DB::connection()->prepare('INSERT INTO Treeni (name, kesto, soveltuvuus, kuvaus) VALUES (:name, :kesto, :soveltuvuus, :kuvaus) RETURNING id');
        // Muistathan, että olion attribuuttiin pääse syntaksilla $this->attribuutin_nimi
        $query->execute(array('name' => $this->name, 'kesto' => $this->kesto, 'soveltuvuus' => $this->soveltuvuus, 'kuvaus' => $this->kuvaus));
        // Haetaan kyselyn tuottama rivi, joka sisältää lisätyn rivin id-sarakkeen arvon
        $row = $query->fetch();
        // Asetetaan lisätyn rivin id-sarakkeen arvo oliomme id-attribuutin arvoksi
        $this->id = $row['id'];
    }
    
    public function update() {
        $query = DB::connection()->prepare('UPDATE Treeni SET (name, kesto, soveltuvuus, kuvaus) = (:name, :kesto, :soveltuvuus, :kuvaus) WHERE id = :id');
        $query->execute(array('id' => $this->id, 'name' => $this->name, 'kesto' => $this->kesto, 'soveltuvuus' => $this->soveltuvuus, 'kuvaus' => $this->kuvaus));
    }
    
    public function delete() {
        $query = DB::connection()->prepare('DELETE FROM Treeni WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

}
