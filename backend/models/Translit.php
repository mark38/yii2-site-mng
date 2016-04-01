<?php

namespace app\models;

use Yii;
use yii\base\Model;
use dosamigos\transliterator\TransliteratorHelper;

class Translit extends Model
{
    public function translitToString($string) {
        return $this->replaceSpaces(TransliteratorHelper::process($string, '', 'en'));
    }

    public function slugify($item, $table, $toColumn, $replacement='-', $currentId=null, $groupColumnName=false, $groupColumnValue=false) {
        $slug = $this->replaceSpaces(TransliteratorHelper::process($item, '', 'en'), $replacement);
        if ($this->checkUniqueSlug($slug, $table, $toColumn, $currentId, $groupColumnName, $groupColumnValue)) {
            return $slug;
        } else {
            for ( $suffix = 2; !$this->checkUniqueSlug($new_slug = $slug.$replacement.$suffix, $table, $toColumn, $currentId, $groupColumnName, $groupColumnValue); $suffix++ ) {}
            return $new_slug;
        }
    }

    private function replaceSpaces( $string, $replacement = '-', $lowercase = true )
    {
        $string = preg_replace( '/[^\p{L}\p{Nd}]+/u', $replacement, $string );
        $string = trim( $string, $replacement );
        return $lowercase ? strtolower( $string ) : $string;
    }

    private function checkUniqueSlug($slug, $table, $toColumn, $currentId=null, $groupColumnName=false, $groupColumnValue=false)
    {
        return !$row = Yii::$app->db->createCommand(
            'SELECT * FROM '.$table.' WHERE '.$toColumn.' like "'.$slug.'"' .
            ($currentId ? ' AND id <> '.$currentId : '') .
            ($groupColumnName && $groupColumnValue ? ' AND '.$groupColumnName.' = '.$groupColumnValue : '')
        )->queryOne();
    }
}