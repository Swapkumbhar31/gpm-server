<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Chapter;
class Module extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'mod_index' => $this->mod_index,
            'chap_count' => Chapter::where('module_id', $this->id)->count(),
            'chapters' => Chapter::where('module_id', $this->id)->get(),
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }


}
