<?php

namespace App\Security\Voter;



class PermissionCalculator
{

    public static function check($data, $entity, $action)
    {
        $response = false;
        foreach ($data["permissions"] as $permission) {
            if ($permission["entityType"] == $entity) {
                foreach ($permission["rights"] as $right) {
                    if ($right["filters"][0]["field"] == "*") {
                        if ($right[$action] == -1)
                            return false;
                        if ($right[$action] == 1)
                            $response = true;
                    }
                }
                return $response;
            }
        }
    }

    public static function checkRight($user, $entity, $list, $action)
    {
        $allPermissions = $user->getAllPermissions();
        $response = [];
        // $autorization permet de déterminer si l'utilisateur avait tous les droits ou aucun
        $authorization = null;
        // Vérifie que la permission concerne la bonne entité
        foreach ($allPermissions as $permissions) {
            foreach ($permissions["permissions"] as $permission) {
                if ($permission["entityType"] == $entity) {
                    foreach ($permission["rights"] as $right) {
                        // Permet de fixer la variable $authorization
                        if ($right["filters"][0]["field"] == "*") {
                            if ($right[$action] == 1) {
                                $response = $list;
                                $authorization = true;
                            } else
                                $authorization = false;
                        } else {
                            // Pour chaque droit, suivant $authorization, ajoute ou supprime un élément de $response
                            $count = 0;
                            foreach ($list as $elem) {
                                $f = "get" . $right["filters"][0]["field"];
                                if (preg_match('/^'.$right["filters"][0]["value"].'$/i', $elem->$f())) {
                                    if ($authorization){
                                        if ($right[$action] == -1) {
                                            array_splice($response, $count, 1);
                                            $count--;
                                        }
                                    } else
                                        if ($right[$action] == 1)
                                            array_push($response, $elem);
                                }
                                $count++;
                            }
                        }
                    }
                }
            }
        }
        return $response;
    }
}