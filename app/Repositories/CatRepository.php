<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cat;

class CatRepository
{
    protected $cat;

    public function __construct()
    {
        $this->cat = new Cat;
    }

    public function getAllCats()
    {
        return $this->cat::orderByDesc('id')->get();
    }

    /**
     * Método para adicionar o gato no banco
     */
    public function addCats($cats)
    {
        $catAdd = new $this->cat();
        $catAdd->name = $cats['name'];
        $catAdd->description = $cats['description'];
        $catAdd->image = $cats['image'];
        $catAdd->updated_at = null;
        return ($catAdd->save()) ? true : false;
    }

    /**
     * Get do gato por ID
     */
    public function getCatId($id_cat)
    {
        return $this->cat::find($id_cat);
    }

    public function getCatName(string $catName)
    {
        return $this->cat::where('name', $catName)->first();
    }

    /**
     * Update do gatinho
     */
    public function updateCat(array $cat)
    {
        $catUp = $this->cat::find($cat['id_cat']);
        if($catUp)
        {
            $catUp->name = $cat['name'];
            $catUp->description = $cat['description'];
            $catUp->updated_at = date('Y-m-d H:i:s',time() - (60 * 60 * 3));
            $catUp->save();

            return true;
        } else {
            return false;
        }
    }

    /**
     * Método para excluir um gato
     */
    public function deleteCat($id_cat) :bool
    {
        $catDel = $this->cat::find($id_cat);
        if($catDel)
        {
            $catDel->delete();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Método para deletar todos os gatos do banco
     */
    public function cleanListCats()
    {
        $this->cat::truncate();
        return true;
    }
}
