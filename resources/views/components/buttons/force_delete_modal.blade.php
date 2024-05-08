@props(['route', 'button_id' , 'data_bs_target' , 'to_be_deleted_text' , 'text' => 'Yes, permanently delete this', 'target_type' => "", 'title'=> "Delete"])

{{-- @php
$class="btn btn-rebeccapurple inline-flex items-center justify-center";
$title="Edit";
$icon="fa-solid fa-edit";
@endphp --}}

<button title={{$title}} class="btn btn btn-danger" data-bs-toggle="modal" data-bs-target="#{{$data_bs_target}}"><i
    class="fa-regular fa-trash-alt"></i>
    <span> {{$title}} </span>
</button>

<div class="modal fade" id="{{$data_bs_target}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Confirm permanent delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to permanently delete this {{$target_type}} and all of its realtions: <strong>{{ $to_be_deleted_text }}</strong>?
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button title="Cancel" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                @include('components.buttons.forcedelete',
                    ['route' => $route, 'button_id' => $button_id , 'text' => $text])
            </div>
        </div>
    </div>
</div>
