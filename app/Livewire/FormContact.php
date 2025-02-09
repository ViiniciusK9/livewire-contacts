<?php

namespace App\Livewire;

use App\Models\Contact;
use Livewire\Attributes\Validate;
use Livewire\Component;

class FormContact extends Component
{
    #[Validate('required|min:3|max:50')]
    public $name;

    #[Validate('required|email|min:5|max:50')]
    public $email;
    
    #[Validate('required|min:5|max:20')]
    public $phone;

    public function newContact()
    {
        $this->validate();

        $result = Contact::firstOrCreate(
            [
                'name' => $this->name,
                'email' => $this->email,
            ],
            [
                'phone' => $this->phone,
            ]
        );

        if ($result->wasRecentlyCreated) {
            $this->reset();
            $this->dispatch('contactAdded');

            // success notification
            $this->dispatch(
                'notification',
                type: 'success',
                title: 'Contact created successfully.',
                position: 'center'
            );
            return;
        }

        // error notification
        $this->dispatch(
            'notification',
            type: 'error',
            title: 'The contact already exists.',
            position: 'center'
        );
    }

    public function render()
    {
        return view('livewire.form-contact');
    }
}
