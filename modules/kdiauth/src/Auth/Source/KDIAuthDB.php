<?php
namespace SimpleSAML\Module\kdiauth\Auth\Source;

require_once('config.php');
require_once('util.php');

use Exception;
use SimpleSAML\Module\core\Auth\UserPassBase;
use SimpleSAML\Module\kdiauth\Auth\Source\sleekdb\Store;

class KDIAuthDB extends UserPassBase
{
    public function __construct($info, $config)
    {
        parent::__construct($info, $config);
        if (!is_string(DATA_FOLDER)) {
            throw new Exception('Missing or invalid DATA_FOLDER option in config.');
        }
    }

    protected function login($username, $password): array
    {
        $users = new Store("IdPBruger", DATA_FOLDER, SLEEKDB_OPTIONS);
        $user = $users->findOneBy([
            ["Username", "=", $username],
            ["Password", "=", $password]
        ]);

        if ($user == null) throw new \SimpleSAML\Error\Error('WRONGUSERPASS');

        $userData = [
            'Name' => $user['Name'],
            'Serial' => $user['Serial'],
            'CVR' => $user['CVR'],
            'PrivilegesIntermediate' => GeneratePrivileges($user['CVR'], unserialize($user['Roles']))
        ];
        
        if (isset($user['error']))
            throw new \SimpleSAML\Error\Error('WRONGUSERPASS');

        return [
            'nameId' => ['C=DK,O=' . $userData['CVR'] . ',CN=' . $userData['Name'] . ',Serial=' . $userData['Serial']],
            'dk:gov:saml:attribute:CvrNumberIdentifier' => [$userData['CVR']],
            'dk:gov:saml:attribute:KombitSpecVer' => ['1.0'],
            'dk:gov:saml:attribute:SpecVer' => ['DK-SAML-2.0'],
            'dk:gov:saml:attribute:AssuranceLevel' => ['3'],
            'dk:gov:saml:attribute:Privileges_intermediate' => [$userData['PrivilegesIntermediate']]
        ];
    }

    private function write_to_file($txt): void {
        // $file = fopen('C:\simplesamlphp\log\testing_log.log', "w") or die("Unable to open file!");
        // fwrite($file, $txt);
        // fclose($file);
        date_default_timezone_set('Europe/Copenhagen');
        $txt = date('m/d/Y h:i:s', time()) . ": " . $txt;
        file_put_contents('C:\simplesamlphp\log\testing_log.log', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
    }
}
?>