<?php declare(strict_types=1);

/**
 * 處理字串加上關鍵字的 highlight 強調
 * @created 2023/07/18
 */

namespace yehchge\yhighlight;

class highlight {

    protected $debug = false;
    protected $LeftHtml = '<span class="hl">';
    protected $RightHtml = '</span>';

    public function __construct()
    {
    }

    /**
     * 主要將標題內含的關鍵字反黃
     * @param  string        $title    標題
     * @param  array||string $keywords 關鍵字陣列
     * @return string                  內含 HTML 反黃的字串
     */
    public function highlightKeywords(string $title, $keywords)
    {
        $highlightedTitle = $title;
        $replacements = array();

        if(is_string($keywords)) $keywords = array($keywords);

        // 取得關鍵字在標題中的位置
        foreach ($keywords as $keyword) {
            if(!$keyword) continue;

            $regex = '/(' . preg_quote($keyword, '/') . ')/i';
            $this->mb_preg_match_all($regex, $title, $matches, PREG_OFFSET_CAPTURE);

            foreach ($matches[1] as $match) {
                $startPos = $match[1];
                $endPos = $startPos + mb_strlen($match[0]) - 1;
                $replacements[] = array($startPos, $endPos, $keyword);
            }
        }

        if($this->debug) print_r($replacements);
        
        // 依照關鍵字開頭位置的先後順序排列
        $replacements = $this->cus_sort($replacements, 0);

        if($this->debug) print_r($replacements);

        $aMergeTmp = array();
        $mergedReplacements = array();
        $lastEndPos = -1;

        $key = 0;

        // 對重疊的關鍵字進行合併處理
        foreach ($replacements as $row) {
            $startPos = $row[0];
            $endPos = $row[1];

            if($this->debug) echo "s =  $startPos, e = $endPos\n";

            if ($startPos > $lastEndPos && $lastEndPos < 0) {
                // 第一次
                $key++;
                $aMergeTmp[$key]['s'] = $startPos;
                $aMergeTmp[$key]['e'] = $endPos;
                $lastEndPos = $endPos;
            } elseif ($startPos == $lastEndPos) {
                $aMergeTmp[$key]['s'] = $aMergeTmp[$key]['s'];
                $aMergeTmp[$key]['e'] = $endPos;
                $lastEndPos = $endPos;
            } elseif ($startPos == $aMergeTmp[$key]['s']) {
                $aMergeTmp[$key]['s'] = $startPos;
                if ($endPos <= $aMergeTmp[$key]['e']) {
                    $aMergeTmp[$key]['e'] = $aMergeTmp[$key]['e'];
                    $lastEndPos = $aMergeTmp[$key]['e'];
                } else {
                    $aMergeTmp[$key]['e'] = $endPos;
                    $lastEndPos = $endPos;
                }
            } elseif ($startPos == ($aMergeTmp[$key]['e'] + 1)) {
                // 關鍵字可以相連在一起
                $aMergeTmp[$key]['s'] = $aMergeTmp[$key]['s'];
                $aMergeTmp[$key]['e'] = $endPos;
                $lastEndPos = $endPos;
            } elseif ($startPos > $lastEndPos) {
                $key++;
                $aMergeTmp[$key]['s'] = $startPos;
                $aMergeTmp[$key]['e'] = $endPos;
                $lastEndPos = $endPos;
            } elseif ($startPos < $lastEndPos && $endPos <= $lastEndPos) {
                // 此關鍵字內含
                continue;
            }
        }

        if($this->debug) print_r($aMergeTmp);

        foreach($aMergeTmp as $row){
            $mergedReplacements[$row['s']] = $row['e'];
        }

        // 替換標題中的關鍵字
        $nextStartPos = 0;
        $nextEndPos = 0;

        foreach ($mergedReplacements as $startPos => $endPos) {
            $keyword = mb_substr($title, $startPos, $endPos - $startPos + 1);
            $highlight = $this->LeftHtml . $keyword . $this->RightHtml;

            $startPos += $nextStartPos;
            $endPos += $nextEndPos;

            // 差異的部份
            $diffNumber = mb_strlen($highlight) - mb_strlen($keyword);
            $nextStartPos += $diffNumber;
            $nextEndPos += $diffNumber;

            $highlightedTitle = $this->mb_substr_replace($highlightedTitle, $highlight, $startPos, mb_strlen($keyword));
        }

        return $highlightedTitle;
    }

    public function setLeftHtml(string $str)
    {
        $this->LeftHtml = $str;
    }

    public function setRightHtml(string $str)
    {
        $this->RightHtml = $str;
    }

    public function setDebug(bool $bug)
    {
        $this->debug = $bug;
    }

    private function cus_sort($array, $id = "", $sort_ascending = true)
    {
        if ($id == "") return $array;

        $temp_array = array();
        $count = count($array);

        for ($i = 0; $i < $count; $i++) {
            $lowest_id = $i;

            for ($j = $i + 1; $j < $count; $j++) {
                if (isset($array[$j][$id]) && isset($array[$lowest_id][$id])) {
                    if ($array[$j][$id] < $array[$lowest_id][$id]) {
                        $lowest_id = $j;
                    }
                }
            }

            $temp = $array[$i];
            $array[$i] = $array[$lowest_id];
            $array[$lowest_id] = $temp;
        }

        if (!$sort_ascending) {
            $array = array_reverse($array);
        }

        return $array;
    }

    private function mb_preg_match_all($ps_pattern, $ps_subject, &$pa_matches, $pn_flags = PREG_PATTERN_ORDER, $pn_offset = 0, $ps_encoding = NULL)
    {
        if (is_null($ps_encoding))
            $ps_encoding = mb_internal_encoding();

        $pn_offset = strlen(mb_substr($ps_subject, 0, $pn_offset, $ps_encoding));
        $ret = preg_match_all($ps_pattern, $ps_subject, $pa_matches, $pn_flags, $pn_offset);

        if ($ret && ($pn_flags & PREG_OFFSET_CAPTURE))
            foreach($pa_matches as &$ha_match)
                foreach($ha_match as &$ha_match)
                    $ha_match[1] = mb_strlen(substr($ps_subject, 0, $ha_match[1]), $ps_encoding);

        return $ret;
    }

    // 從 mb_substr_replace 中移植的替代函數
    private function mb_substr_replace($string, $replacement, $start, $length = null)
    {
        if (is_array($string)) {
            $numStrings = count($string);
            $numReplacements = count($replacement);
            if ($numReplacements !== $numStrings) {
                trigger_error('The number of replacements does not match the number of strings in the subject', E_USER_WARNING);
                return false;
            }
            for ($i = 0; $i < $numStrings; ++$i) {
                $string[$i] = mb_substr_replace($string[$i], $replacement[$i], $start, $length);
            }
            return $string;
        }

        $string_length = (is_null($length)) ? mb_strlen($string) : $length;
        $start = ($start >= 0) ? $start : max(0, mb_strlen($string) + $start);
        $before = mb_substr($string, 0, $start);
        $after = mb_substr($string, $start + $string_length);
        return $before . $replacement . $after;
    }

}
