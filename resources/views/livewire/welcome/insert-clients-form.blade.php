<?php

use App\Models\Client;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use App\Enums\Family;

new #[Layout('layouts.app')] class extends Component {
    use WithFileUploads;

    #[Rule('required|string|max:100')]
    public string $first_name = '';
    #[Rule('required|string|max:100')]
    public string $second_name = '';
    #[Rule('required|string|max:100')]
    public string $third_name = '';
    #[Rule('string|email|max:100|unique:' . Client::class)]
    public string $mail = '';
    #[Rule('required|string|max:10000')]
    public string $about_me = '';
    #[Rule(['phones.*' => 'string'])]
    public array $phones = [];
    #[Rule(['files.*' => 'max:5120|image|mimes:png,jpg,pdf'])]
    public $files = [];
    #[Rule('required')]
    public string $family = '';
    #[Rule('required')]
    public string $birthday = '';
    public bool $confirm = false;
    public int $i = 1;

    public $family_massive = [];

    public function boot()
    {
        $this->withValidator(function ($validator) {
            $validator->after(function ($validator) {
                $this->resetValidation(['mail', 'confirm']);
                if (empty(array_filter(array_values($this->phones), fn($n) => !empty($n))) and empty($this->mail)) {
                    $validator->errors()->add('mail', 'we need a phone or mail required');
                }
                if (!$this->confirm) {
                    $validator->errors()->add('confirm', 'please put checkbox');
                }
                if (count($this->files) > 5) {
                    $validator->errors()->add('files', 'You can select only 5 images');
                }
            });
        });
    }

    public function mount()
    {
        $this->family_massive = Family::cases();
        $this->addRowToList($this->i);
    }

    public function addRowToList($i)
    {
        if ($i < 6) {
            $this->phones[$i] = '';
        }
        $this->i = $i++;
    }

    public function save(): void
    {
        $validated = $this->validate();
        $filesName = [];
        foreach ($this->files as $file) {
            $filesName[] = $file->store('files');
        }

        $validated['files'] = json_encode($filesName);
        $validated['phones'] = json_encode(array_values($this->phones));

        Client::create($validated);

        $this->redirect('/success/', navigate: true);
    }
}; ?>

<div>
    <form wire:submit="save">
        <div>
            <x-input-label for="first_name" value="First Name"/>
            <x-text-input wire:model.blur="first_name" id="first_name" class="block mt-1 w-full" type="text"
                          name="first_name" style="{{($errors->get('first_name') ? 'border:red 3px solid' : '')}}"
                          required/>
            <x-input-error :messages="$errors->get('first_name')" class="mt-2"/>
        </div>
        <div>
            <x-input-label for="second_name" value="Second Name"/>
            <x-text-input wire:model.blur="second_name" id="second_name" class="block mt-1 w-full" type="text"
                          name="second_name" style="{{($errors->get('second_name') ? 'border:red 3px solid' : '')}}"
                          required/>
            <x-input-error :messages="$errors->get('second_name')" class="mt-2"/>
        </div>
        <div>
            <x-input-label for="third_name" value="Third Name"/>
            <x-text-input wire:model.blur="third_name" id="third_name" class="block mt-1 w-full" type="text"
                          name="third_name" style="{{($errors->get('third_name') ? 'border:red 3px solid' : '')}}"
                          required/>
            <x-input-error :messages="$errors->get('third_name')" class="mt-2"/>
        </div>
        <div>
            <x-input-label for="birthday" value="Birthday"/>
            <x-text-input wire:model.blur="birthday" type="date" id="birthday" class="block mt-1 w-full" name="birthday"
                          style="{{($errors->get('birthday') ? 'border:red 3px solid' : '')}}" required/>
            <x-input-error :messages="$errors->get('birthday')" class="mt-2"/>
        </div>
        <div>
            <x-input-label for="mail" value="E-mail"/>
            <x-text-input wire:model.blur="mail" id="mail" class="block mt-1 w-full" type="text" name="mail"/>
            <x-input-error :messages="$errors->get('mail')" class="mt-2"/>
        </div>
        <div id="phone_div">
            <x-input-label for="phone" value="Phone"/>
            @for($i=1; $i<=count($phones) ; $i++)
                <x-text-input
                    x-data="imageViewer({{$i}})"
                    wire:key="phones-{{$i}}" wire:model.blur="phones.{{$i}}" type="tel" id="phones{{$i}}"
                    class="phone block mt-1 w-full" placeholder="" name="phones[]" value="" wire:ignore/>
                <x-input-error :messages="$errors->get('phone.{{$i}}')" class="mt-2"/>
            @endfor
            <button id="add_phone" style="padding:10px; border:1px solid white; border-radius: 25%; color:white;"
                    wire:click="addRowToList({{$i}})">+
            </button>
        </div>
        <div>
            <x-input-label for="family" value="Family Status"
                           style="{{($errors->get('family') ? 'border:red 3px solid' : '')}}"/>
            <select required
                    class="form-select block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    id="family" aria-label="Default select example" name="family" wire:model.blur="family">
                <option value="" selected>Open this select menu</option>
                @foreach ($family_massive as $case) {
                <option value="{{ $case->value }}">{{ $case->value }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('family')" class="mt-2"/>
        </div>
        <div>
            <x-input-label for="about_me" value="About Me"
                           style="{{($errors->get('about_me') ? 'border:red 3px solid' : '')}}"/>
            <textarea class=" block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900
                           dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500
                           dark:focus:ring-indigo-600 rounded-md shadow-sm
            " name="about_me" style="max-height: 170px;" wire:model.blur="about_me" maxlength="1000"></textarea>
            <x-input-error :messages="$errors->get('about_me')" class="mt-2"/>
        </div>
        <div class="mb-3">
            <x-input-label for="file" value="Multiple files"/>
            <input class="form-control" type="file" id="formFileMultiple" multiple name="files" wire:model.blur="files">
            <x-input-error :messages="$errors->get('files')" class="mt-2"/>
        </div>
        <div class="mb-3">
            <input type="checkbox" id="confirm" name="confirm" wire:model.blur="confirm"/>
            <label for="confirm" style="color:white">I have read the rules</label>
            <x-input-error :messages="$errors->get('confirm')" class="mt-2"/>
        </div>
        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ml-4">
                Save
            </x-primary-button>
        </div>
    </form>
</div>
<script>
    function imageViewer($i) {
        $('#phones' + $i).intlTelInput({
            preferredCountries: ['us', 'ca'],
            defaultCountry: '',
            separateDialCode: false,
            initialCountry: '',
            autoFormat: true,
        }).on('countrychange', function (e, countryData) {
            $('#phones' + $i).val('+' + ($('#phones' + $i).intlTelInput('getSelectedCountryData').dialCode));
        });
    }
</script>
