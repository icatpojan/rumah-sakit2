<?php

namespace Plugins\Master\Src;

use Systems\Lib\QueryWrapper;
use Systems\MySQL;

class JenjangJabatan
{

    protected function db($table)
    {
        return new QueryWrapper($table);
    }

    public function getIndex()
    {

      $totalRecords = $this->mysql('jnj_jabatan')
        ->select('kode')
        ->toArray();
      $offset         = 10;
      $return['halaman']    = 1;
      $return['jml_halaman']    = ceil(count($totalRecords) / $offset);
      $return['jumlah_data']    = count($totalRecords);

      $return['list'] = $this->mysql('jnj_jabatan')
        ->desc('kode')
        ->limit(10)
        ->toArray();

      return $return;

    }

    public function anyForm()
    {
        if (isset($_POST['kode'])){
          $return['form'] = $this->mysql('jnj_jabatan')->where('kode', $_POST['kode'])->oneArray();
        } else {
          $return['form'] = [
            'kode' => '',
            'nama' => '',
	          'tnj' => '',
	          'indek' => ''
          ];
        }

        return $return;
    }

    public function anyDisplay()
    {

        $perpage = '10';
        $totalRecords = $this->mysql('jnj_jabatan')
          ->select('kode')
          ->toArray();
        $offset         = 10;
        $return['halaman']    = 1;
        $return['jml_halaman']    = ceil(count($totalRecords) / $offset);
        $return['jumlah_data']    = count($totalRecords);

        $return['list'] = $this->mysql('jnj_jabatan')
          ->desc('kode')
          ->offset(0)
          ->limit($perpage)
          ->toArray();

        if(isset($_POST['cari'])) {
          $return['list'] = $this->mysql('jnj_jabatan')
            ->like('kode', '%'.$_POST['cari'].'%')
            ->orLike('nama', '%'.$_POST['cari'].'%')
	          ->orLike('tnj', '%'.$_POST['cari'].'%')
            ->desc('kode')
            ->offset(0)
            ->limit($perpage)
            ->toArray();
          $jumlah_data = count($return['list']);
          $jml_halaman = ceil($jumlah_data / $offset);
        }
        if(isset($_POST['halaman'])){
          $offset     = (($_POST['halaman'] - 1) * $perpage);
          $return['list'] = $this->mysql('jnj_jabatan')
            ->desc('kode')
            ->offset($offset)
            ->limit($perpage)
            ->toArray();
          $return['halaman'] = $_POST['halaman'];
        }

        return $return;
    }

    public function postSave()
    {
      if (!$this->mysql('jnj_jabatan')->where('kode', $_POST['kode'])->oneArray()) {
        $query = $this->mysql('jnj_jabatan')->save($_POST);
      } else {
        $query = $this->mysql('jnj_jabatan')->where('kode', $_POST['kode'])->save($_POST);
      }
      return $query;
    }

    public function postHapus()
    {
      return $this->mysql('jnj_jabatan')->where('kode', $_POST['kode'])->delete();
    }

    protected function mysql($table = NULL)
    {
        return new MySQL($table);
    }

}
