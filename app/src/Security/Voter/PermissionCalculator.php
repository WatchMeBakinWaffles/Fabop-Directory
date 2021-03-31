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
                        if ($right[$action])
                            $response = true;
                    }
                }
                return $response;
            }
        }
    }

    public static function checkList($user, $entity, $list)
    {
        $data = $user->getAllPermissions();
        $response = [];
        $authorization = null;
        foreach ($data as $permissions) {
            foreach ($permissions["permissions"] as $permission) {
                if ($permission["entityType"] == $entity) {
                    foreach ($permission["rights"] as $right) {
                        if ($right["filters"][0]["field"] == "*") {
                            if ($right["read"]) {
                                $response = $list;
                                $authorization = true;
                            } else
                                $authorization = false;
                        }
                        $count = 0;
                        foreach ($list as $elem) {
                            $temp = $right["filters"][0]["field"];
                            $f = "get" . $temp;
                            if ($right["filters"][0]["field"] != "*") {
                                if (preg_match('/\b' . $right["filters"][0]["value"] . '\b/i', $elem->$f())) {
                                    if ($authorization)
                                        if ($right["read"] == -1) {
                                            array_splice($response, $count, 1);
                                        } else
                                            if ($right["read"])
                                                array_push($response, $elem);
                                }
                            }
                            $count++;
                        }
                    }
                }
            }
        }
        return $response;
    }
}