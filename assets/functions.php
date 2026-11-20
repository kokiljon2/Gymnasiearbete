<?php

function registreraKund($pdo, $namn, $email, $pwd, $tnummer)
{

    $sql = "SELECT id FROM kund WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);

    if ($stmt->fetch()) {
        return false;
    }

    $hash = password_hash($pwd, PASSWORD_DEFAULT);

    $sql = "INSERT INTO kund (namn, email, password, tnummer)
            VALUES (:namn, :email, :password, :tnummer)";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        'namn' => $namn,
        'email' => $email,
        'password' => $hash,
        'tnummer' => $tnummer
    ]);
}

function loggaInKund($pdo, $email, $pwd)
{

    $sql = "SELECT * FROM kund WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);

    $kund = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($kund && password_verify($pwd, $kund['password'])) {

        session_regenerate_id(true);

        $_SESSION['kund_id'] = $kund['id'];
        $_SESSION['kund_namn'] = $kund['namn'];

        return true;
    }

    return false;
}

function registreraPersonal($pdo, $namn, $pwd, $grupp)
{

    if (!harRoll('Huvudadmin')) {
        return false;
    }

    $tillåtnaRoller = ['Personal', 'Admin', 'Ekonom'];

    if (!in_array($grupp, $tillåtnaRoller, true)) {
        return false;
    }

    if (empty($namn) || empty($pwd)) {
        return false;
    }

    $sql = "SELECT uid FROM personal WHERE namn = :namn";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['namn' => $namn]);

    if ($stmt->fetch()) {
        return false;
    }

    $hash = password_hash($pwd, PASSWORD_DEFAULT);

    $sql = "INSERT INTO personal (pwd, namn, grupp)
            VALUES (:pwd, :namn, :grupp)";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        'pwd' => $hash,
        'namn' => $namn,
        'grupp' => $grupp
    ]);
}

function loggaInPersonal($pdo, $namn, $pwd)
{

    $sql = "SELECT * FROM personal WHERE namn = :namn";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['namn' => $namn]);

    $personal = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($personal && password_verify($pwd, $personal['pwd'])) {

        session_regenerate_id(true);

        $_SESSION['personal_id'] = $personal['uid'];
        $_SESSION['personal_namn'] = $personal['namn'];
        $_SESSION['personal_grupp'] = $personal['grupp'];

        return true;
    }

    return false;
}

function harRoll($roll)
{

    return isset($_SESSION['personal_grupp']) &&
        $_SESSION['personal_grupp'] === $roll;
}

function arPersonalInloggad()
{

    return isset($_SESSION['personal_id']);
}

function arKundInloggad()
{

    return isset($_SESSION['kund_id']);
}

function hamtaKundProfil($pdo, $kundId)
{

    $sql = "SELECT id, namn, medlemslutdatum, email, tnummer
            FROM kund
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $kundId]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function uppdateraKundProfil($pdo, $kundId, $namn, $email, $tnummer)
{

    $sql = "UPDATE kund
            SET namn = :namn,
                email = :email,
                tnummer = :tnummer
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        'namn' => $namn,
        'email' => $email,
        'tnummer' => $tnummer,
        'id' => $kundId
    ]);
}

function hamtaLokaler($pdo)
{

    $sql = "SELECT * FROM lokal";

    $stmt = $pdo->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function hamtaLokal($pdo, $adr)
{

    $sql = "SELECT * FROM lokal WHERE adr = :adr";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['adr' => $adr]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function arLokalLedig($pdo, $adr, $intid, $uttid)
{

    $sql = "SELECT COUNT(*) FROM bokning
            WHERE adr = :adr
            AND intid < :uttid
            AND uttid > :intid";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'adr' => $adr,
        'intid' => $intid,
        'uttid' => $uttid
    ]);

    return $stmt->fetchColumn() == 0;
}

function skapaBokning($pdo, $kundId, $adr, $intid, $uttid, $kostnad)
{

    if (!arKundInloggad() || $_SESSION['kund_id'] != $kundId) {
        return false;
    }


    if (strtotime($uttid) <= strtotime($intid)) {
        return false;
    }

    $lokal = hamtaLokal($pdo, $adr);

    if (!$lokal) {
        return false;
    }


    if (!arLokalLedig($pdo, $adr, $intid, $uttid)) {
        return false;
    }

    $sql = "INSERT INTO bokning
            (kund_id, adr, intid, uttid, kostnad)
            VALUES (:kund_id, :adr, :intid, :uttid, :kostnad)";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        'kund_id' => $kundId,
        'adr' => $adr,
        'intid' => $intid,
        'uttid' => $uttid,
        'kostnad' => $kostnad
    ]);
}

function hamtaKundensBokningar($pdo, $kundId)
{

    $sql = "SELECT * FROM bokning
            WHERE kund_id = :kund_id
            ORDER BY intid DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['kund_id' => $kundId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function avbokaBokning($pdo, $bokningId, $kundId)
{

    if (!arKundInloggad() || $_SESSION['kund_id'] != $kundId) {
        return false;
    }

    $sql = "DELETE FROM bokning
            WHERE id = :id
            AND kund_id = :kund_id
            AND intid > NOW()";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'id' => $bokningId,
        'kund_id' => $kundId
    ]);

    return $stmt->rowCount() > 0;
}

function hamtaBetalsatt($pdo, $kundId)
{

    $sql = "SELECT id, namn, giltigdatum
            FROM betalsatt
            WHERE kund_id = :kund_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['kund_id' => $kundId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function taBortBetalsatt($pdo, $betalsattId, $kundId)
{

    if (!arKundInloggad() || $_SESSION['kund_id'] != $kundId) {
        return false;
    }

    $sql = "DELETE FROM betalsatt
            WHERE id = :id
            AND kund_id = :kund_id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'id' => $betalsattId,
        'kund_id' => $kundId
    ]);

    return $stmt->rowCount() > 0;
}

?>