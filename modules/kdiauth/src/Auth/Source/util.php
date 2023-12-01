<?php
use SimpleSAML\Module\kdiauth\Auth\Source\sleekdb\Store;
use SimpleSAML\Module\kdiauth\Auth\Source\sleekdb\Query;

function IsLoggedIn() {
    return (isset($_SESSION['User']));
}

function IsSuperAdmin() {
    return ($_SESSION['User']['SuperAdmin'] == "yes");
}

function HttpParam($param) {
    if (isset($_GET[$param])) {
        return trim($_GET[$param]);
    } elseif (isset($_POST[$param])) {
        return trim($_POST[$param]);
    } else {
        return "";
    }
}


/* EXAMPLE:
<PrivilegeList xmlns=\"http://itst.dk/oiosaml/basic_privilege_profile\">
    <PrivilegeGroup Scope="urn:dk:gov:saml:cvrNumberIdentifier:19435075">
        <Privilege>http://fagsystem.dk/roles/usersystemrole/godkend_faktura/1</Privilege>
        <Constraint Name="http://sts.demo.dk/constraints/beloeb/1">25000</Constraint>
        <Constraint Name="http://sts.demo.dk/constraints/afdeling/1">IT Afdelingen</Constraint>
    </PrivilegeGroup>
</PrivilegeList>
*/
function GeneratePrivileges($cvr, $userRoles)
{
    $privs = "<PrivilegeList xmlns=\"http://itst.dk/oiosaml/basic_privilege_profile\">\n";

    // We only save the ID on the user roles, so we need the assiciative arrays of all roles and
    // constraints so we can get corresponding entityIds

    $allRoles = GetAllRoles($cvr);
    $allConstraints = GetAllConstraints();    

    foreach ($userRoles as $userRole)
    {
        $roleEntityId = $allRoles[$userRole['_id']]['EntityId'];
        $privs .= "\t<PrivilegeGroup Scope=\"urn:dk:gov:saml:cvrNumberIdentifier:$cvr\">\n";
        $privs .= "\t\t<Privilege>$roleEntityId</Privilege>\n";
            foreach ($userRole['constraints'] as $roleConstraint)
            {
                $constraintEntityId = $allConstraints[$roleConstraint['_id']]['EntityId'];                
                $constraintValue = $roleConstraint['value'];
                $privs .= "\t\t".'<Constraint Name="'.$constraintEntityId.'">'.$constraintValue.'</Constraint>'."\n";
            }
        $privs .= "\t</PrivilegeGroup>\n";
    }
    $privs .= "</PrivilegeList>";
    return base64_encode($privs);
}

function MsgBoxSuccess($msg)
{
    return '<span class="alert alert-success ml15 font13">'.$msg.'</span>';
}

function MsgBoxError($msg)
{
    return '<div class="mt15 my-alert"><i class="bi bi-exclamation-circle"></i> '.$msg.'</div>';
}

// Roles and constraints are referred to with IDs, so in order to show name/EntityId in UI
// we make associative array with _id as key

function GetAllRoles($cvr)
{
    $tmp = [];
    $db = new Store("Roller", DATA_FOLDER, SLEEKDB_OPTIONS);    
    $items = $db->findBy(["CVR", "=", $cvr], ["EntityId" => "asc"]);
    foreach ($items as $item)
    {
        $tmp[$item['_id']]['_id'] = $item['_id'];
        $tmp[$item['_id']]['EntityId'] = $item['EntityId'];
        $tmp[$item['_id']]['Constraints'] = $item['Constraints'];
    }
    return $tmp;
}

function GetAllConstraints()
{
    $tmp = [];
    $db = new Store("Constraints", DATA_FOLDER, SLEEKDB_OPTIONS);    
    $items = $db->findAll(["Name" => "asc"]);
    foreach ($items as $item)
    {
        $tmp[$item['_id']]['_id'] = $item['_id'];
        $tmp[$item['_id']]['EntityId'] = $item['EntityId'];
        $tmp[$item['_id']]['Name'] = $item['Name'];
    }
    return $tmp;
}

function ShowToast($header, $msg)
{
    echo('
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header navbar_kombit">
        <i class="bi bi-exclamation-square"></i> <span class="ml15">&nbsp;</span>
        <strong class="me-auto ml15">'.$header.'</strong>
        <!--<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>-->
      </div>
      <div class="toast-body">
        '.$msg.'
      </div>
    </div>
  </div>
  <script>
    const toastLiveExample = document.getElementById(\'liveToast\');
    const toast = new bootstrap.Toast(toastLiveExample);
    toast.show();
  </script>
    ');
}
?>