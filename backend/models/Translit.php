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

    public function slugify($item, $table, $toColumn, $replacement='-', $currentId=null) {
        $slug = $this->replaceSpaces(TransliteratorHelper::process($item, '', 'en'), $replacement);
        if ($this->checkUniqueSlug($slug, $table, $toColumn, $currentId)) {
            return $slug;
        } else {
            for ( $suffix = 2; !$this->checkUniqueSlug($new_slug = $slug . '_' . $suffix, $table, $toColumn, $currentId); $suffix++ ) {}
            return $new_slug;
        }
    }

    private function replaceSpaces( $string, $replacement = '-', $lowercase = true )
    {
        $string = preg_replace( '/[^\p{L}\p{Nd}]+/u', $replacement, $string );
        $string = trim( $string, $replacement );
        return $lowercase ? strtolower( $string ) : $string;
    }

    private function checkUniqueSlug($slug, $table, $toColumn, $currentId)
    {
        return !$row = Yii::$app->db->createCommand('SELECT * FROM '.$table.' WHERE '.$toColumn.' like "'.$slug.'"'.($currentId ? ' AND id <> '.$currentId : ''))->queryOne();
    }
}