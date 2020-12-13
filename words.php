<script type="text/javascript">

        <?php
            $data = fetchWords();
            foreach($data as $row)
            {
                $id[] = $row['ID'];
                $en[] = $row['EN_WORDS'];
                $tr[] = $row['TR_WORDS'];
                $learning[] = $row['LEARNING'];
                $learned[] = $row['LEARNED'];
            }
            $sizeId = sizeof($id);
        ?>
        var enWords = <?php echo json_encode($en); ?>; // ingilizce kelimeleri dizi içine alıyor.
        var trWords = <?php echo json_encode($tr); ?>; // kelimelerin türkçe karşılığını alıyor.
        var sizeId = <?php echo json_encode($sizeId); ?>; // kaç kelime var veri olarak alıyor.
</script>