<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class User extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'profile_img_url' => $this->profile_img_url,
            'email' => $this->email,
            'contact' => $this->contact,
            'membership' => $this->membership,
            'pancard' => $this->pancard,
            'chapter_id' => $this->chapter_id,
            'address' => $this->address,
            'city' => $this->city,
            'isVarified' => $this->isVarified,
            'adminApproval' => $this->adminApproval,
            'isModuleCompleted' => $this->isModuleCompleted,
            'state' => $this->state,
            'pancard_img_url' => $this->pancard_img_url,
            'referral_code' => $this->referral_code,
            'dob' => $this->dob,
            'isAdmin' => $this->isAdmin,
            'bankdetails' => $this->bankdetails,
        ];
    }
}
