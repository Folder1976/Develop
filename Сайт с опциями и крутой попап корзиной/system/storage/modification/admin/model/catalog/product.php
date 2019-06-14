<?php
class ModelCatalogProduct extends Model {

        public function checkOctIfTableExist($table_name) {
          return $this->db->query("
            SELECT
              COUNT(*) as total
            FROM information_schema.TABLES
            WHERE
              TABLE_SCHEMA = '".DB_DATABASE."'
            AND
              TABLE_NAME = '".$table_name."'
          ")->row['total'];
        }

        public function checkIfColumnExist($table_name, $table_column) {
          return $this->db->query("
            SELECT
              COUNT(*) as total
            FROM information_schema.COLUMNS
            WHERE
              TABLE_SCHEMA = '".DB_DATABASE."'
            AND
              TABLE_NAME = '".$table_name."'
            AND
              COLUMN_NAME  = '".$table_column."'
          ")->row['total'];
        }

        public function oct_conver_cyrillic($string, $replace = false) {
          $array = array(
            "А"=>"a",
            "Б"=>"b",
            "В"=>"v",
            "Г"=>"g",
            "Д"=>"d",
            "Е"=>"e",
            "Ё"=>"yo",
            "Ж"=>"zh",
            "З"=>"z",
            "И"=>"i",
            "Й"=>"j",
            "К"=>"k",
            "Л"=>"l",
            "М"=>"m",
            "Н"=>"n",
            "О"=>"o",
            "П"=>"p",
            "Р"=>"r",
            "С"=>"s",
            "Т"=>"t",
            "У"=>"u",
            "Ф"=>"f",
            "Х"=>"kh",
            "Ц"=>"ts",
            "Ч"=>"ch",
            "Ш"=>"sh",
            "Щ"=>"sch",
            "Ъ"=>"",
            "Ы"=>"y",
            "Ь"=>"",
            "Э"=>"e",
            "Ю"=>"yu",
            "Я"=>"ya",
            "а"=>"a",
            "б"=>"b",
            "в"=>"v",
            "г"=>"g",
            "д"=>"d",
            "е"=>"e",
            "ё"=>"yo",
            "ж"=>"zh",
            "з"=>"z",
            "и"=>"i",
            "й"=>"j",
            "к"=>"k",
            "л"=>"l",
            "м"=>"m",
            "н"=>"n",
            "о"=>"o",
            "п"=>"p",
            "р"=>"r",
            "с"=>"s",
            "т"=>"t",
            "у"=>"u",
            "ф"=>"f",
            "х"=>"kh",
            "ц"=>"ts",
            "ч"=>"ch",
            "ш"=>"sh",
            "щ"=>"sch",
            "ъ"=>"",
            "ы"=>"y",
            "ь"=>"",
            "э"=>"e",
            "ю"=>"yu",
            "я"=>"ya",
            ","=>"_",
            "."=>"_",
            "-"=>"_",
            "`"=>"_"
          );

          $string = preg_replace('/\s{2,}/ui', ' ', $string);
          $string = preg_replace('/[^a-zA-Zа-яА-Я0-9_,\s]/ui', '', $string);
          $string = preg_replace('/\s/ui', '_', $string);

          if ($replace) {
            return strtr($this->oct_normalize_string(trim($string), true), $array);
          } else {
            return strtr($this->oct_normalize_string(trim($string)), $array);
          }
        }

        public function oct_normalize_string($string, $replace = false, $lower = true) {
          $result = trim($string);

          if ($lower) {
            $result = mb_strtolower($result);
          }

          if ($replace) {
            $result = str_replace(" ", "_", $result);
          }

          return $result;
        }

        public function addOctProductFilterData($product_id, $data, $delete = true) {
          $this->load->model('localisation/language');
          $oct_languages = $this->model_localisation_language->getLanguages();
          $oct_product_stickers_data = $this->config->get('oct_product_stickers_data');
          $oct_product_filter_status = $this->config->get('oct_product_filter_status');

          if ($oct_product_filter_status) {
            if (isset($oct_product_stickers_data['status']) && $oct_product_stickers_data['status']) {
              if ($oct_languages && isset($data['oct_product_stickers'])) {
                foreach ($oct_languages as $oct_language) {
                  foreach ($data['oct_product_stickers'] as $oct_product_sticker_id) {
                    if ($delete) {
                      $this->db->query("
                        DELETE
                        FROM ".DB_PREFIX."oct_filter_product_sticker
                        WHERE
                          product_id = '".(int)$product_id."'
                        AND
                          product_sticker_id = '".(int)$oct_product_sticker_id."'
                      ");
                    }

                    $oct_sticker_info = $this->db->query("
                      SELECT
                        DISTINCT *
                      FROM ".DB_PREFIX."oct_product_stickers pst
                      LEFT JOIN ".DB_PREFIX."oct_product_stickers_description pstd ON (pst.product_sticker_id = pstd.product_sticker_id)
                      WHERE
                        pst.product_sticker_id = '".(int)$oct_product_sticker_id."'
                      AND
                        pstd.language_id = '".(int)$oct_language['language_id']."'
                    ")->row;

                    if ($oct_sticker_info) {
                      $this->db->query("
                        INSERT INTO ".DB_PREFIX."oct_filter_product_sticker
                        SET
                          product_id = '".(int)$product_id."',
                          language_id  = '".(int)$oct_language['language_id']."',
                          product_sticker_id = '".(int)$oct_sticker_info['product_sticker_id']."',
                          product_sticker_value = '".$this->db->escape($this->oct_normalize_string($oct_sticker_info['text'], false, false))."',
                          product_sticker_value_mod = '".$this->db->escape($this->oct_conver_cyrillic($oct_sticker_info['text'], true))."_".(int)$oct_sticker_info['product_sticker_id']."'
                      ");
                    }
                  }
                }
              }
            }

            if ( isset($data['product_attribute']) ) {
              foreach ($data['product_attribute'] as $product_attribute) {
                if ($product_attribute['attribute_id']) {
                  if ($delete) {
                    $this->db->query("
                      DELETE
                      FROM ".DB_PREFIX."oct_filter_product_attribute
                      WHERE
                        product_id = '".(int)$product_id."'
                      AND
                        attribute_id = '".(int)$product_attribute['attribute_id']."'
                    ");
                  }

                  foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
                    $oct_attribute_data = $this->db->query("
                      SELECT
                        a.attribute_group_id,
                        a.sort_order,
                        ad.name
                      FROM ".DB_PREFIX."attribute a
                      LEFT JOIN ".DB_PREFIX."attribute_description ad ON (a.attribute_id = ad.attribute_id)
                      WHERE
                        a.attribute_id = '".(int)$product_attribute['attribute_id']."'
                      AND
                        ad.language_id = '".(int)$language_id."'
                    ")->row;

                    if ($delete) {
                      $this->db->query("
                        DELETE
                        FROM ".DB_PREFIX."oct_filter_product_attribute
                        WHERE
                          product_id = '".(int)$product_id."'
                        AND
                          attribute_id = '".(int)$product_attribute['attribute_id']."'
                        AND
                          language_id = '".(int)$language_id."'
                      ");
                    }

                    if ($oct_attribute_data) {
                      $this->db->query("
                        INSERT INTO ".DB_PREFIX."oct_filter_product_attribute
                        SET
                          product_id = '".(int)$product_id."',
                          attribute_id = '".(int)$product_attribute['attribute_id']."',
                          attribute_group_id = '".(int)$oct_attribute_data['attribute_group_id']."',
                          sort_order = '".(int)$oct_attribute_data['sort_order']."',
                          language_id = '".(int)$language_id."',
                          attribute_value = '".$this->db->escape($this->oct_normalize_string($product_attribute_description['text'], false, false))."',
                          attribute_value_mod = '".$this->db->escape($this->oct_conver_cyrillic($product_attribute_description['text'], true))."_".(int)$product_attribute['attribute_id']."',
                          attribute_name = '".$this->db->escape($this->oct_normalize_string($oct_attribute_data['name'], false, false))."',
                          attribute_name_mod = '".$this->db->escape($this->oct_conver_cyrillic($oct_attribute_data['name'], true))."_".(int)$product_attribute['attribute_id']."'
                      ");
                    }
                  }
                }
              }
            }

            if (isset($data['product_option'])) {
              foreach ($data['product_option'] as $product_option) {
                if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
                  if (isset($product_option['product_option_value'])) {
                    if ($oct_languages) {
                      foreach ($oct_languages as $oct_language) {
                        $oct_option_info = $this->db->query("
                          SELECT *
                          FROM `".DB_PREFIX."option` o
                          LEFT JOIN ".DB_PREFIX."option_description od ON (o.option_id = od.option_id)
                          WHERE
                            o.option_id = '".(int)$product_option['option_id']."'
                          AND
                            od.language_id = '".(int)$oct_language['language_id']."'
                        ")->row;

                        foreach ($product_option['product_option_value'] as $product_option_value) {
                          $oct_option_value_info = $this->db->query("
                            SELECT *
                            FROM ".DB_PREFIX."option_value ov
                            LEFT JOIN ".DB_PREFIX."option_value_description ovd ON (ov.option_value_id = ovd.option_value_id)
                            WHERE
                              ov.option_value_id = '".(int)$product_option_value['option_value_id']."'
                            AND
                              ovd.language_id = '".(int)$oct_language['language_id']."'
                          ")->row;

                          if ($oct_option_value_info) {
                            $this->db->query("
                              INSERT INTO ".DB_PREFIX."oct_filter_product_option
                              SET
                                product_id = '".(int)$product_id."',
                                language_id = '".(int)$oct_language['language_id']."',
                                option_id = '".(int)$product_option['option_id']."',
                                option_name = '".$this->db->escape($this->oct_normalize_string($oct_option_info['name'], false, false))."',
                                option_name_mod = '".$this->db->escape($this->oct_conver_cyrillic($oct_option_info['name'], true))."_".(int)$product_option['option_id']."',
                                option_value_id = '".(int)$product_option_value['option_value_id']."',
                                option_value_name  = '".$this->db->escape($oct_option_value_info['name'])."',
                                option_value_name_mod = '".$this->db->escape($this->oct_conver_cyrillic($oct_option_value_info['name'], true))."_".(int)$product_option['option_id']."',
                                option_value_image = '".$this->db->escape($oct_option_value_info['image'])."'
                            ");
                          }
                        }
                      }
                    }
                  }
                }
              }
            }

            if (isset($data['product_filter'])) {
              foreach ($data['product_filter'] as $filter_id) {
                if ($oct_languages) {
                  foreach ($oct_languages as $oct_language) {
                    $oct_standard_info = $this->db->query("
                      SELECT
                        *,
                        (SELECT
                          name
                        FROM ".DB_PREFIX."filter_group_description fgd
                        WHERE
                          f.filter_group_id = fgd.filter_group_id
                        AND
                          fgd.language_id = '".(int)$oct_language['language_id']."'
                        ) AS `group`
                      FROM ".DB_PREFIX."filter f
                      LEFT JOIN ".DB_PREFIX."filter_description fd ON (f.filter_id = fd.filter_id)
                      WHERE
                        f.filter_id = '".(int)$filter_id."'
                      AND
                        fd.language_id = '".(int)$oct_language['language_id']."'
                    ")->row;

                    if ($oct_standard_info) {
                      $this->db->query("
                        INSERT INTO ".DB_PREFIX."oct_filter_product_standard
                        SET
                          product_id = '".(int)$product_id."',
                          sort_order = '".(int)$oct_standard_info['sort_order']."',
                          language_id = '".(int)$oct_language['language_id']."',
                          filter_id = '".(int)$filter_id."',
                          filter_group_id = '".(int)$oct_standard_info['filter_group_id']."',
                          filter_name = '".$this->db->escape($this->oct_normalize_string($oct_standard_info['group'], false, false))."',
                          filter_name_mod = '".$this->db->escape($this->oct_conver_cyrillic($oct_standard_info['group'], true))."_".(int)$oct_standard_info['filter_group_id']."',
                          filter_value = '".$this->db->escape($this->oct_normalize_string($oct_standard_info['name'], false, false))."',
                          filter_value_mod = '".$this->db->escape($this->oct_conver_cyrillic($oct_standard_info['name'], true))."_".(int)$filter_id."'
                      ");
                    }
                  }
                }
              }
            }

            if (isset($data['oct_product_manufacturers'])) {
              if ($this->checkOctIfTableExist(DB_PREFIX."manufacturer_description") && $this->checkIfColumnExist(DB_PREFIX . "manufacturer_description", "name")) {
                foreach ($data['oct_product_manufacturers'] as $manufacturer_id) {
                  if ($oct_languages) {
                    foreach ($oct_languages as $oct_language) {
                      $oct_manufacturer_info = $this->db->query("
                        SELECT
                          m.manufacturer_id,
                          md.name,
                          m.image
                        FROM `".DB_PREFIX."manufacturer` m
                        LEFT JOIN ".DB_PREFIX."manufacturer_description md ON (m.manufacturer_id = md.manufacturer_id)
                        WHERE
                          m.manufacturer_id = '".(int)$manufacturer_id."'
                        AND
                          md.language_id = '".(int)$oct_language['language_id']."'
                      ")->row;

                      if ($oct_manufacturer_info) {
                        $this->db->query("
                          INSERT INTO ".DB_PREFIX."oct_filter_product_manufacturer
                          SET
                            product_id = '".(int)$product_id."',
                            language_id = '".(int)$oct_language['language_id']."',
                            manufacturer_id = '".(int)$manufacturer_id."',
                            manufacturer_name = '".$this->db->escape($this->oct_normalize_string($oct_manufacturer_info['name'], false, false))."',
                            manufacturer_image = '".$this->db->escape($oct_manufacturer_info['image'])."'
                        ");
                      }
                    }
                  }
                }
              } else {
                foreach ($data['oct_product_manufacturers'] as $manufacturer_id) {
                  if ($oct_languages) {
                    foreach ($oct_languages as $oct_language) {
                      $oct_manufacturer_info = $this->db->query("
                        SELECT
                          m.manufacturer_id,
                          m.name,
                          m.image
                        FROM `".DB_PREFIX."manufacturer` m
                        WHERE
                          m.manufacturer_id = '".(int)$manufacturer_id."'
                      ")->row;

                      if ($oct_manufacturer_info) {
                        $this->db->query("
                          INSERT INTO ".DB_PREFIX."oct_filter_product_manufacturer
                          SET
                            product_id = '".(int)$product_id."',
                            language_id = '".(int)$oct_language['language_id']."',
                            manufacturer_id = '".(int)$manufacturer_id."',
                            manufacturer_name = '".$this->db->escape($this->oct_normalize_string($oct_manufacturer_info['name'], false, false))."',
                            manufacturer_image = '".$this->db->escape($oct_manufacturer_info['image'])."'
                        ");
                      }
                    }
                  }
                }
              }
            }
          }
        }

        public function editOctProductFilterData($product_id, $data) {
          $this->load->model('localisation/language');
          $oct_languages = $this->model_localisation_language->getLanguages();
          $oct_product_stickers_data = $this->config->get('oct_product_stickers_data');
          $oct_product_filter_status = $this->config->get('oct_product_filter_status');

          if ($oct_product_filter_status) {
            if (isset($oct_product_stickers_data['status']) && $oct_product_stickers_data['status']) {
              $this->db->query("DELETE FROM ".DB_PREFIX."oct_filter_product_sticker WHERE product_id = '".(int)$product_id."'");

              if ($oct_languages && isset($data['oct_product_stickers'])) {
                foreach ($oct_languages as $oct_language) {
                  foreach ($data['oct_product_stickers'] as $oct_product_sticker_id) {
                    $this->db->query("
                      DELETE
                      FROM ".DB_PREFIX."oct_filter_product_sticker
                      WHERE
                        product_id = '".(int)$product_id."'
                      AND
                        product_sticker_id = '".(int)$oct_product_sticker_id."'
                    ");

                    $oct_sticker_info = $this->db->query("
                      SELECT
                        DISTINCT *
                      FROM ".DB_PREFIX."oct_product_stickers pst
                      LEFT JOIN ".DB_PREFIX."oct_product_stickers_description pstd ON (pst.product_sticker_id = pstd.product_sticker_id)
                      WHERE
                        pst.product_sticker_id = '".(int)$oct_product_sticker_id."'
                      AND
                        pstd.language_id = '".(int)$oct_language['language_id']."'
                    ")->row;

                    if ($oct_sticker_info) {
                      $this->db->query("
                        INSERT INTO ".DB_PREFIX."oct_filter_product_sticker
                        SET
                          product_id = '".(int)$product_id."',
                          language_id  = '".(int)$oct_language['language_id']."',
                          product_sticker_id = '".(int)$oct_sticker_info['product_sticker_id']."',
                          product_sticker_value = '".$this->db->escape($this->oct_normalize_string($oct_sticker_info['text'], false, false))."',
                          product_sticker_value_mod = '".$this->db->escape($this->oct_conver_cyrillic($oct_sticker_info['text'], true))."_".(int)$oct_sticker_info['product_sticker_id']."'
                      ");
                    }
                  }
                }
              }
            }

            $this->db->query("DELETE FROM ".DB_PREFIX."oct_filter_product_attribute WHERE product_id = '".(int)$product_id."'");

            if (!empty($data['product_attribute'])) {
              foreach ($data['product_attribute'] as $product_attribute) {
                if ($product_attribute['attribute_id']) {
                  $this->db->query("
                    DELETE
                    FROM ".DB_PREFIX."oct_filter_product_attribute
                    WHERE
                      product_id = '".(int)$product_id."'
                    AND
                      attribute_id = '".(int)$product_attribute['attribute_id']."'
                  ");

                  foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
                    $oct_attribute_data = $this->db->query("
                      SELECT
                        a.attribute_group_id,
                        a.sort_order,
                        ad.name
                      FROM ".DB_PREFIX."attribute a
                      LEFT JOIN ".DB_PREFIX."attribute_description ad ON (a.attribute_id = ad.attribute_id)
                      WHERE
                        a.attribute_id = '".(int)$product_attribute['attribute_id']."'
                      AND
                        ad.language_id = '".(int)$language_id."'
                    ")->row;

                    if ($oct_attribute_data) {
                      $this->db->query("
                        INSERT INTO ".DB_PREFIX."oct_filter_product_attribute
                        SET
                          product_id = '".(int)$product_id."',
                          attribute_id = '".(int)$product_attribute['attribute_id']."',
                          attribute_group_id = '".(int)$oct_attribute_data['attribute_group_id']."',
                          sort_order = '".(int)$oct_attribute_data['sort_order']."',
                          language_id = '".(int)$language_id."',
                          attribute_value = '".$this->db->escape($this->oct_normalize_string($product_attribute_description['text'], false, false))."',
                          attribute_value_mod = '".$this->db->escape($this->oct_conver_cyrillic($product_attribute_description['text'], true))."_".(int)$product_attribute['attribute_id']."',
                          attribute_name = '".$this->db->escape($this->oct_normalize_string($oct_attribute_data['name'], false, false))."',
                          attribute_name_mod = '".$this->db->escape($this->oct_conver_cyrillic($oct_attribute_data['name'], true))."_".(int)$product_attribute['attribute_id']."'
                      ");
                    }
                  }
                }
              }
            }

            $this->db->query("DELETE FROM ".DB_PREFIX."oct_filter_product_option WHERE product_id = '".(int)$product_id."'");

            if (isset($data['product_option'])) {
              foreach ($data['product_option'] as $product_option) {
                if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
                  if (isset($product_option['product_option_value'])) {
                    if ($oct_languages) {
                      foreach ($oct_languages as $oct_language) {
                        $oct_option_info = $this->db->query("
                          SELECT *
                          FROM `".DB_PREFIX."option` o
                          LEFT JOIN ".DB_PREFIX."option_description od ON (o.option_id = od.option_id)
                          WHERE
                            o.option_id = '".(int)$product_option['option_id']."'
                          AND
                            od.language_id = '".(int)$oct_language['language_id']."'
                        ")->row;

                        foreach ($product_option['product_option_value'] as $product_option_value) {
                          $oct_option_value_info = $this->db->query("
                            SELECT *
                            FROM ".DB_PREFIX."option_value ov
                            LEFT JOIN ".DB_PREFIX."option_value_description ovd ON (ov.option_value_id = ovd.option_value_id)
                            WHERE
                              ov.option_value_id = '".(int)$product_option_value['option_value_id']."'
                            AND
                              ovd.language_id = '".(int)$oct_language['language_id']."'
                          ")->row;

                          if ($oct_option_value_info) {
                            $this->db->query("
                              INSERT INTO ".DB_PREFIX."oct_filter_product_option
                              SET
                                product_id = '".(int)$product_id."',
                                language_id = '".(int)$oct_language['language_id']."',
                                option_id = '".(int)$product_option['option_id']."',
                                option_name = '".$this->db->escape($oct_option_info['name'])."',
                                option_name_mod = '".$this->db->escape($this->oct_conver_cyrillic($oct_option_info['name'], true))."_".(int)$product_option['option_id']."',
                                option_value_id  = '".(int)$product_option_value['option_value_id']."',
                                option_value_name  = '".$this->db->escape($oct_option_value_info['name'])."',
                                option_value_name_mod = '".$this->db->escape($this->oct_conver_cyrillic($oct_option_value_info['name'], true))."_".(int)$product_option['option_id']."',
                                option_value_image = '".$this->db->escape($oct_option_value_info['image'])."'
                            ");
                          }
                        }
                      }
                    }
                  }
                }
              }
            }

            $this->db->query("DELETE FROM ".DB_PREFIX."oct_filter_product_standard WHERE product_id = '".(int)$product_id."'");

            if (isset($data['product_filter'])) {
              foreach ($data['product_filter'] as $filter_id) {
                if ($oct_languages) {
                  foreach ($oct_languages as $oct_language) {
                    $oct_standard_info = $this->db->query("
                      SELECT
                        *,
                        (SELECT
                          name
                        FROM ".DB_PREFIX."filter_group_description fgd
                        WHERE
                          f.filter_group_id = fgd.filter_group_id
                        AND
                          fgd.language_id = '".(int)$oct_language['language_id']."'
                        ) AS `group`
                      FROM ".DB_PREFIX."filter f
                      LEFT JOIN ".DB_PREFIX."filter_description fd ON (f.filter_id = fd.filter_id)
                      WHERE
                        f.filter_id = '".(int)$filter_id."'
                      AND
                        fd.language_id = '".(int)$oct_language['language_id']."'
                    ")->row;

                    if ($oct_standard_info) {
                      $this->db->query("
                        INSERT INTO ".DB_PREFIX."oct_filter_product_standard
                        SET
                          product_id = '".(int)$product_id."',
                          sort_order = '".(int)$oct_standard_info['sort_order']."',
                          language_id = '".(int)$oct_language['language_id']."',
                          filter_id = '".(int)$filter_id."',
                          filter_group_id = '".(int)$oct_standard_info['filter_group_id']."',
                          filter_name = '".$this->db->escape($this->oct_normalize_string($oct_standard_info['group'], false, false))."',
                          filter_name_mod = '".$this->db->escape($this->oct_conver_cyrillic($oct_standard_info['group'], true))."_".(int)$oct_standard_info['filter_group_id']."',
                          filter_value = '".$this->db->escape($this->oct_normalize_string($oct_standard_info['name'], false, false))."',
                          filter_value_mod = '".$this->db->escape($this->oct_conver_cyrillic($oct_standard_info['name'], true))."_".(int)$filter_id."'
                      ");
                    }
                  }
                }
              }
            }

            $this->db->query("DELETE FROM ".DB_PREFIX."oct_filter_product_manufacturer WHERE product_id = '".(int)$product_id."'");

            if (isset($data['oct_product_manufacturers'])) {
              if ($this->checkOctIfTableExist(DB_PREFIX."manufacturer_description") && $this->checkIfColumnExist(DB_PREFIX . "manufacturer_description", "name")) {
                foreach ($data['oct_product_manufacturers'] as $manufacturer_id) {
                  if ($oct_languages) {
                    foreach ($oct_languages as $oct_language) {
                      $oct_manufacturer_info = $this->db->query("
                        SELECT
                          m.manufacturer_id,
                          md.name,
                          m.image
                        FROM `".DB_PREFIX."manufacturer` m
                        LEFT JOIN ".DB_PREFIX."manufacturer_description md ON (m.manufacturer_id = md.manufacturer_id)
                        WHERE
                          m.manufacturer_id = '".(int)$manufacturer_id."'
                        AND
                          md.language_id = '".(int)$oct_language['language_id']."'
                      ")->row;

                      if ($oct_manufacturer_info) {
                        $this->db->query("
                          INSERT INTO ".DB_PREFIX."oct_filter_product_manufacturer
                          SET
                            product_id = '".(int)$product_id."',
                            language_id = '".(int)$oct_language['language_id']."',
                            manufacturer_id = '".(int)$manufacturer_id."',
                            manufacturer_name = '".$this->db->escape($this->oct_normalize_string($oct_manufacturer_info['name'], false, false))."',
                            manufacturer_image = '".$this->db->escape($oct_manufacturer_info['image'])."'
                        ");
                      }
                    }
                  }
                }
              } else {
                foreach ($data['oct_product_manufacturers'] as $manufacturer_id) {
                  if ($oct_languages) {
                    foreach ($oct_languages as $oct_language) {
                      $oct_manufacturer_info = $this->db->query("
                        SELECT
                          m.manufacturer_id,
                          m.name,
                          m.image
                        FROM `".DB_PREFIX."manufacturer` m
                        WHERE
                          m.manufacturer_id = '".(int)$manufacturer_id."'
                      ")->row;

                      if ($oct_manufacturer_info) {
                        $this->db->query("
                          INSERT INTO ".DB_PREFIX."oct_filter_product_manufacturer
                          SET
                            product_id = '".(int)$product_id."',
                            language_id = '".(int)$oct_language['language_id']."',
                            manufacturer_id = '".(int)$manufacturer_id."',
                            manufacturer_name = '".$this->db->escape($this->oct_normalize_string($oct_manufacturer_info['name'], false, false))."',
                            manufacturer_image = '".$this->db->escape($oct_manufacturer_info['image'])."'
                        ");
                      }
                    }
                  }
                }
              }
            }
          }
        }

        public function refreshOctProductFilterData($type = false) {
          $query = $this->db->query("SELECT DISTINCT product_id FROM ".DB_PREFIX."product WHERE status = '1'")->rows;

          if ($query) {
            if ($type == 'attribute') {
              $this->db->query("TRUNCATE ".DB_PREFIX."oct_filter_product_attribute");
            }

            if ($type == 'filter') {
              $this->db->query("TRUNCATE ".DB_PREFIX."oct_filter_product_standard");
            }

            if ($type == 'option') {
              $this->db->query("TRUNCATE ".DB_PREFIX."oct_filter_product_option");
            }

            if ($type == 'stickers') {
              $this->db->query("TRUNCATE ".DB_PREFIX."oct_filter_product_sticker");
            }

            if ($type == 'manufacturers') {
              $this->db->query("TRUNCATE ".DB_PREFIX."oct_filter_product_manufacturer");
            }

            foreach ($query as $product) {
              $data = array();

              if ($type == 'attribute') {
                $data['product_attribute'] = $this->getProductAttributes($product['product_id']);
              }

              if ($type == 'filter') {
                $data['product_filter'] = $this->getProductFilters($product['product_id']);
              }

              if ($type == 'option') {
                $data['product_option'] = $this->getProductOptions($product['product_id']);
              }

              if ($type == 'stickers') {
                $data['oct_product_stickers'] = $this->getOctProductFilterStickers($product['product_id']);
              }

              if ($type == 'manufacturers') {
                $data['oct_product_manufacturers'] = $this->getOctProductFilterManufacturers($product['product_id']);
              }

              $this->addOctProductFilterData($product['product_id'], $data, false);
            }
          }
        }

        public function getOctProductFilterStickers($product_id) {
          $oct_product_sticker_data = array();

          $oct_product_stickers_data_config = $this->config->get('oct_product_stickers_data');

          if (isset($oct_product_stickers_data_config['status']) && $oct_product_stickers_data_config['status']) {
            $query = $this->db->query("
              SELECT
                oct_product_stickers
              FROM ".DB_PREFIX."product
              WHERE
                product_id = '".(int)$product_id."'
            ")->rows;

            if ($query) {
              foreach ($query as $product) {
                if (isset($product['oct_product_stickers']) && $product['oct_product_stickers']) {
                  $product_sticker_ids = unserialize($product['oct_product_stickers']);
                  if ($product_sticker_ids) {
                    foreach ($product_sticker_ids as $product_sticker_id) {
                      $oct_product_sticker_data[] = $product_sticker_id;
                    }
                  }
                }
              }
            }
          }

          return $oct_product_sticker_data;
        }

        public function getOctProductFilterManufacturers($product_id) {
          $query = $this->db->query("
            SELECT
              manufacturer_id
            FROM ".DB_PREFIX."product
            WHERE
              product_id = '".(int)$product_id."'
          ")->rows;

          $oct_product_manufacturer_data = array();

          if ($query) {
            foreach ($query as $product) {
              if (isset($product['manufacturer_id']) && $product['manufacturer_id']) {
                $oct_product_manufacturer_data[] = $product['manufacturer_id'];
              }
            }
          }

          return $oct_product_manufacturer_data;
        }
      
	public function addProduct($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW()");

		$product_id = $this->db->getLastId();

        $this->addOctProductFilterData($product_id, $data);
      


			$oct_product_stickers_data = $this->config->get('oct_product_stickers_data');
			
			if (isset($oct_product_stickers_data['status']) && $oct_product_stickers_data['status']) {
				$this->db->query("UPDATE " . DB_PREFIX . "product SET oct_product_stickers = '" . $this->db->escape(isset($data['oct_product_stickers']) ? serialize($data['oct_product_stickers']) : '') . "' WHERE product_id = '" . (int)$product_id . "'");
			}
			
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}

		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}


        $oct_product_tabs_data = $this->config->get('oct_product_tabs_data');
        
        if (isset($oct_product_tabs_data['status']) && $oct_product_tabs_data['status']) {
          if (isset($data['oct_product_extra_tab'])) {
            foreach ($data['oct_product_extra_tab'] as $oct_product_extra_tab) {
              if ($oct_product_extra_tab['extra_tab_id']) {
                foreach ($oct_product_extra_tab['oct_product_extra_tab_description'] as $language_id => $oct_product_extra_tab_description) {
                  $this->db->query("INSERT INTO " . DB_PREFIX . "oct_product_extra_tabs SET product_id = '" . (int)$product_id . "', extra_tab_id = '" . (int)$oct_product_extra_tab['extra_tab_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($oct_product_extra_tab_description['text']) . "'");
                }
              }
            }
          }
        }
      
		if (isset($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					// Removes duplicates
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "' AND language_id = '" . (int)$language_id . "'");

						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}

		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					if (isset($product_option['product_option_value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

						$product_option_id = $this->db->getLastId();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}

		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}

		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}

		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		if(isset($data['main_category_id']) && $data['main_category_id'] > 0) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' AND category_id = '" . (int)$data['main_category_id'] . "'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$data['main_category_id'] . "', main_category = 1");
		} elseif(isset($data['product_category'][0])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product_to_category SET main_category = 1 WHERE product_id = '" . (int)$product_id . "' AND category_id = '" . (int)$data['product_category'][0] . "'");
		}

		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $product_reward) {
				if ((int)$product_reward['points'] > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$product_reward['points'] . "'");
				}
			}
		}

		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		if (isset($data['product_recurring'])) {
			foreach ($data['product_recurring'] as $recurring) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_recurring` SET `product_id` = " . (int)$product_id . ", customer_group_id = " . (int)$recurring['customer_group_id'] . ", `recurring_id` = " . (int)$recurring['recurring_id']);
			}
		}
	// << Related Options / Связанные опции 
			
			if ( !$this->model_extension_module_related_options ) {
				$this->load->model('extension/module/related_options');
			}
			
			$this->model_extension_module_related_options->set_ro_data($product_id, ( isset($data) ? $data :array('ro_data_included'=>true) ) );
			
			// >> Related Options / Связанные опции
		$this->cache->delete('product');

		return $product_id;
	}

	public function editProduct($product_id, $data) {

        $this->editOctProductFilterData($product_id, $data);
      
		$this->db->query("UPDATE " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");


			$oct_product_stickers_data = $this->config->get('oct_product_stickers_data');
			
			if (isset($oct_product_stickers_data['status']) && $oct_product_stickers_data['status']) {
				$this->db->query("UPDATE " . DB_PREFIX . "product SET oct_product_stickers = '" . $this->db->escape(isset($data['oct_product_stickers']) ? serialize($data['oct_product_stickers']) : '') . "' WHERE product_id = '" . (int)$product_id . "'");
			}
			
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");

		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}


        $oct_product_tabs_data = $this->config->get('oct_product_tabs_data');
        
        if (isset($oct_product_tabs_data['status']) && $oct_product_tabs_data['status']) {
          $this->db->query("DELETE FROM " . DB_PREFIX . "oct_product_extra_tabs WHERE product_id = '" . (int)$product_id . "'");

          if (!empty($data['oct_product_extra_tab'])) {
            foreach ($data['oct_product_extra_tab'] as $oct_product_extra_tab) {
              if ($oct_product_extra_tab['extra_tab_id']) {
                foreach ($oct_product_extra_tab['oct_product_extra_tab_description'] as $language_id => $oct_product_extra_tab_description) {
                  $this->db->query("INSERT INTO " . DB_PREFIX . "oct_product_extra_tabs SET product_id = '" . (int)$product_id . "', extra_tab_id = '" . (int)$oct_product_extra_tab['extra_tab_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($oct_product_extra_tab_description['text']) . "'");
                }
              }
            }
          }
        }
      
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");

		if (!empty($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					// Removes duplicates
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					if (isset($product_option['product_option_value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

						$product_option_id = $this->db->getLastId();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "', product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");

			// oct_images_by_options start
			$oct_images_by_options_data = $this->config->get('oct_images_by_options_data');
			
			if (isset($oct_images_by_options_data['status']) && $oct_images_by_options_data['status']) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "oct_product_image_by_option WHERE product_id = '" . (int)$product_id . "'");
			}
			// oct_images_by_options end
			

		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");

			// oct_images_by_options start
			if (isset($oct_images_by_options_data['status']) && $oct_images_by_options_data['status']) {
				$product_image_id = $this->db->getLastId();

				if (isset($product_image['image_by_option'])) {
            		foreach ($product_image['image_by_option'] as $option_value_id) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "oct_product_image_by_option SET product_id = '" . (int)$product_id . "', product_image_id = '" . (int)$product_image_id . "', option_value_id = '" . (int)$option_value_id . "'");
					}
				}
			}
			// oct_images_by_options end
			
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		if(isset($data['main_category_id']) && $data['main_category_id'] > 0) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' AND category_id = '" . (int)$data['main_category_id'] . "'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$data['main_category_id'] . "', main_category = 1");
		} elseif(isset($data['product_category'][0])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product_to_category SET main_category = 1 WHERE product_id = '" . (int)$product_id . "' AND category_id = '" . (int)$data['product_category'][0] . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $value) {
				if ((int)$value['points'] > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$value['points'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_recurring` WHERE product_id = " . (int)$product_id);

		if (isset($data['product_recurring'])) {
			foreach ($data['product_recurring'] as $product_recurring) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_recurring` SET `product_id` = " . (int)$product_id . ", customer_group_id = " . (int)$product_recurring['customer_group_id'] . ", `recurring_id` = " . (int)$product_recurring['recurring_id']);
			}
		}
	// << Related Options / Связанные опции 
			
			if ( !$this->model_extension_module_related_options ) {
				$this->load->model('extension/module/related_options');
			}
			
			$this->model_extension_module_related_options->set_ro_data($product_id, ( isset($data) ? $data :array('ro_data_included'=>true) ) );
			
			// >> Related Options / Связанные опции
		$this->cache->delete('product');
	}


			// oct_images_by_options start
			public function getProductOptionsToImage($product_id, $product_image_id) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "oct_product_image_by_option WHERE product_id = '" . (int)$product_id . "' AND product_image_id = '" . (int)$product_image_id . "'");

				$option_value_id_data = array();

				if ($query->rows) {
	            	foreach ($query->rows as $row) {
						$option_value_id_data[] = $row['option_value_id'];
					}
				}

				return $option_value_id_data;
			}
			// oct_images_by_options end
			
	public function copyProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p WHERE p.product_id = '" . (int)$product_id . "'");

		if ($query->num_rows) {
			$data = $query->row;

			$data['sku'] = '';
			$data['upc'] = '';
			$data['viewed'] = '0';
			$data['keyword'] = '';
			$data['status'] = '0';


			$oct_product_stickers_data = $this->config->get('oct_product_stickers_data');
			
			if (isset($oct_product_stickers_data['status']) && $oct_product_stickers_data['status']) {
				$data['oct_product_stickers'] = array();
			}
			
			$data['product_attribute'] = $this->getProductAttributes($product_id);
			$data['product_description'] = $this->getProductDescriptions($product_id);
			$data['product_discount'] = $this->getProductDiscounts($product_id);
			$data['product_filter'] = $this->getProductFilters($product_id);
			$data['product_image'] = $this->getProductImages($product_id);
			$data['product_option'] = $this->getProductOptions($product_id);
			$data['product_related'] = $this->getProductRelated($product_id);
			$data['product_reward'] = $this->getProductRewards($product_id);
			$data['product_special'] = $this->getProductSpecials($product_id);
			$data['product_category'] = $this->getProductCategories($product_id);
			$data['product_download'] = $this->getProductDownloads($product_id);
			$data['product_layout'] = $this->getProductLayouts($product_id);
			$data['product_store'] = $this->getProductStores($product_id);
			$data['product_recurrings'] = $this->getRecurrings($product_id);

			$data['main_category_id'] = $this->getProductMainCategoryId($product_id);
			// << Related Options / Связанные опции 
			
			if ( !$this->model_extension_module_related_options ) {
				$this->load->model('extension/module/related_options');
			}
			
			$data['ro_data'] = $this->model_extension_module_related_options->get_ro_data($product_id);
			foreach ($data['ro_data'] as &$rodt) {
				$rodt['rovp_id'] = 0;
			}
			unset($rodt);
			
			// >> Related Options / Связанные опции
			$this->addProduct($data);
		}
	}

	public function deleteProduct($product_id) {

        $this->db->query("DELETE FROM ".DB_PREFIX."oct_filter_product_option WHERE product_id = '".(int)$product_id."'");
        $this->db->query("DELETE FROM ".DB_PREFIX."oct_filter_product_attribute WHERE product_id = '".(int)$product_id."'");
        $this->db->query("DELETE FROM ".DB_PREFIX."oct_filter_product_sticker WHERE product_id = '".(int)$product_id."'");
        $this->db->query("DELETE FROM ".DB_PREFIX."oct_filter_product_standard WHERE product_id = '".(int)$product_id."'");
      
		$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");

        $oct_product_tabs_data = $this->config->get('oct_product_tabs_data');
        
        if (isset($oct_product_tabs_data['status']) && $oct_product_tabs_data['status']) {
          $this->db->query("DELETE FROM " . DB_PREFIX . "oct_product_extra_tabs WHERE product_id = '" . (int)$product_id . "'");

          if (!empty($data['oct_product_extra_tab'])) {
            foreach ($data['oct_product_extra_tab'] as $oct_product_extra_tab) {
              if ($oct_product_extra_tab['extra_tab_id']) {
                foreach ($oct_product_extra_tab['oct_product_extra_tab_description'] as $language_id => $oct_product_extra_tab_description) {
                  $this->db->query("INSERT INTO " . DB_PREFIX . "oct_product_extra_tabs SET product_id = '" . (int)$product_id . "', extra_tab_id = '" . (int)$oct_product_extra_tab['extra_tab_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($oct_product_extra_tab_description['text']) . "'");
                }
              }
            }
          }
        }
      
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");

			// oct_images_by_options start
			$oct_images_by_options_data = $this->config->get('oct_images_by_options_data');
        
			if (isset($oct_images_by_options_data['status']) && $oct_images_by_options_data['status']) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "oct_product_image_by_option WHERE product_id = '" . (int)$product_id . "'");
			}
			// oct_images_by_options end
			
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_recurring WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE product_id = '" . (int)$product_id . "'");

		$this->cache->delete('product');
	}

	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "' LIMIT 1) AS keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getProducts($data = array()) {

			$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

			if (!empty($data['filter_category'])) {
        $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
			}

			$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";


		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			//$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
			// << Related Options / Связанные опции 
				$ro_settings = $this->config->get('related_options');
				if (isset($ro_settings['spec_model']) && $ro_settings['spec_model']) {
					if ($ro_settings['spec_model'] == 1) {
						$sql .= " AND (p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'
												OR p.product_id IN ( SELECT product_id FROM ".DB_PREFIX."relatedoptions WHERE model LIKE '" . $this->db->escape($data['filter_model']) . "%' ) )";
					} else {
						$sql .= " AND (p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'
												OR p.product_id IN ( SELECT product_id FROM ".DB_PREFIX."relatedoptions_search WHERE model LIKE '" . $this->db->escape($data['filter_model']) . "%' ) )";
					}
				} else {
					$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
				}
			
				// >> Related Options / Связанные опции
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_image']) && !is_null($data['filter_image'])) {
			if ($data['filter_image'] == 1) {
				$sql .= " AND (p.image IS NOT NULL AND p.image <> '' AND p.image <> 'no_image.png')";
			} else {
				$sql .= " AND (p.image IS NULL OR p.image = '' OR p.image = 'no_image.png')";
			}
		}

        if (!empty($data['filter_category'])) {
            $sql .= " AND p2c.category_id = '" . (int)$data['filter_category'] . "'";
        }
		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getProductsByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	}

	public function getProductDescriptions($product_id) {
		$product_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_h1'          => $result['meta_h1'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'tag'              => $result['tag']
			);
		}

		return $product_description_data;
	}

	public function getProductCategories($product_id) {
		$product_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}

	public function getProductFilters($product_id) {
		$product_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_filter_data[] = $result['filter_id'];
		}

		return $product_filter_data;
	}


        public function getProductExtraTabs($product_id) {
          $oct_product_extra_tab_data = array();

          $oct_product_extra_tab_query = $this->db->query("SELECT extra_tab_id FROM " . DB_PREFIX . "oct_product_extra_tabs WHERE product_id = '" . (int)$product_id . "' GROUP BY extra_tab_id");

          foreach ($oct_product_extra_tab_query->rows as $oct_product_extra_tab) {
            $oct_product_extra_tab_description_data = array();

            $oct_product_extra_tab_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "oct_product_extra_tabs WHERE product_id = '" . (int)$product_id . "' AND extra_tab_id = '" . (int)$oct_product_extra_tab['extra_tab_id'] . "'");

            foreach ($oct_product_extra_tab_description_query->rows as $oct_product_extra_tab_description) {
              $oct_product_extra_tab_description_data[$oct_product_extra_tab_description['language_id']] = array('text' => $oct_product_extra_tab_description['text']);
            }

            $oct_product_extra_tab_data[] = array(
              'extra_tab_id'                  => $oct_product_extra_tab['extra_tab_id'],
              'oct_product_extra_tab_description' => $oct_product_extra_tab_description_data
            );
          }

          return $oct_product_extra_tab_data;
        }
      
	public function getProductAttributes($product_id) {
		$product_attribute_data = array();

		$product_attribute_query = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' GROUP BY attribute_id");

		foreach ($product_attribute_query->rows as $product_attribute) {
			$product_attribute_description_data = array();

			$product_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

			foreach ($product_attribute_description_query->rows as $product_attribute_description) {
				$product_attribute_description_data[$product_attribute_description['language_id']] = array('text' => $product_attribute_description['text']);
			}

			$product_attribute_data[] = array(
				'attribute_id'                  => $product_attribute['attribute_id'],
				'product_attribute_description' => $product_attribute_description_data
			);
		}

		return $product_attribute_data;
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON(pov.option_value_id = ov.option_value_id) WHERE pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' ORDER BY ov.sort_order ASC");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'points'                  => $product_option_value['points'],
					'points_prefix'           => $product_option_value['points_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}

	public function getProductOptionValue($product_id, $product_option_value_id) {
		$query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' ORDER BY quantity, priority, price");

		return $query->rows;
	}

	public function getProductSpecials($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");

		return $query->rows;
	}

	public function getProductRewards($product_id) {
		$product_reward_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_reward_data[$result['customer_group_id']] = array('points' => $result['points']);
		}

		return $product_reward_data;
	}

	public function getProductDownloads($product_id) {
		$product_download_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_download_data[] = $result['download_id'];
		}

		return $product_download_data;
	}

	public function getProductStores($product_id) {
		$product_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_store_data[] = $result['store_id'];
		}

		return $product_store_data;
	}

	public function getProductLayouts($product_id) {
		$product_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $product_layout_data;
	}

	public function getProductMainCategoryId($product_id) {
		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' AND main_category = '1' LIMIT 1");

		return ($query->num_rows ? (int)$query->row['category_id'] : 0);
	}

	public function getProductRelated($product_id) {
		$product_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_related_data[] = $result['related_id'];
		}

		return $product_related_data;
	}

	public function getRecurrings($product_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_recurring` WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}

	public function getTotalProducts($data = array()) {
    $sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			//$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
			// << Related Options / Связанные опции 
				$ro_settings = $this->config->get('related_options');
				if (isset($ro_settings['spec_model']) && $ro_settings['spec_model']) {
					if ($ro_settings['spec_model'] == 1) {
						$sql .= " AND (p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'
												OR p.product_id IN ( SELECT product_id FROM ".DB_PREFIX."relatedoptions WHERE model LIKE '" . $this->db->escape($data['filter_model']) . "%' ) )";
					} else {
						$sql .= " AND (p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'
												OR p.product_id IN ( SELECT product_id FROM ".DB_PREFIX."relatedoptions_search WHERE model LIKE '" . $this->db->escape($data['filter_model']) . "%' ) )";
					}
				} else {
					$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
				}
			
				// >> Related Options / Связанные опции
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

    if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
			$sql .= " AND p2c.category_id = '" . (int)$data['filter_category'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_image']) && !is_null($data['filter_image'])) {
			if ($data['filter_image'] == 1) {
				$sql .= " AND (p.image IS NOT NULL AND p.image <> '' AND p.image <> 'no_image.png')";
			} else {
				$sql .= " AND (p.image IS NULL OR p.image = '' OR p.image = 'no_image.png')";
			}
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalProductsByTaxClassId($tax_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByStockStatusId($stock_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByWeightClassId($weight_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByLengthClassId($length_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE length_class_id = '" . (int)$length_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByDownloadId($download_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_download WHERE download_id = '" . (int)$download_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByManufacturerId($manufacturer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByAttributeId($attribute_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByOptionId($option_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_option WHERE option_id = '" . (int)$option_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByProfileId($recurring_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_recurring WHERE recurring_id = '" . (int)$recurring_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
}
