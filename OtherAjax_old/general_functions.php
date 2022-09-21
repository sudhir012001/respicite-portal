<?php
function level_color_mapper($level)
    {
        $level_color = '';
        
            if ($level == 'Strength' || $level== 'Highly Dominant'){
                $level_color = '74,191,27';
            }
            else if($level=='Dominant')
            {
                $level_color = '95,250,33';
            }
            else if ($level == 'Needs attention' || $level== 'Less Dominant'){
                $level_color = '234,250,33';
            }else
            if ($level == 'Needs immediate attention' || $level== 'Non Dominant'){
                $level_color = '247,89,73';
            }
            else{
                $level_color = '214,211,211';
            }
         
       
        return $level_color;
    }

    
    ?>
