<?php
    include "database/database.php";
    $sql = "SELECT * FROM `words` WHERE `LEARNED` = 0";
    $queryEdit = mysqli_query($dbconnect, $sql);
    if(mysqli_num_rows($queryEdit) > 0)
    {
        while($data = mysqli_fetch_assoc($queryEdit))
        {
            print <<<yaz
            <tr>
                <td>
                    <button id="edit" type="button" data-toggle="modal" data-target="#exampleModal" disabled="disabled" data-whatever="{$data['ID']}_{$data['EN_WORDS']}_{$data['TR_WORDS']}"><i class="fas fa-edit"></i></button>
                </td>
                <td>
                    {$data['EN_WORDS']}
                </td>
                <td class="hide hiden">
                    {$data['TR_WORDS']}
                </td>
            </tr>
            yaz;
        }
    }
    mysqli_close($dbconnect);
?>