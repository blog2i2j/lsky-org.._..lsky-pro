@section('title', '设置')

<x-app-layout>
    <div class="my-6 md:my-10">
        <form action="{{ route('settings.update') }}" method="POST">
            <div class="overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="email" class="block text-sm font-medium text-gray-700">邮箱</label>
                            <x-input type="text" id="email" autocomplete="email" value="{{ Auth::user()->email }}" disabled readonly/>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-700">昵称</label>
                            <x-input type="text" name="name" id="name" autocomplete="name" value="{{ Auth::user()->name }}"/>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="default_strategy" class="block text-sm font-medium text-gray-700">默认上传策略</label>
                            <x-select id="default_strategy" name="configs[default_strategy]" autocomplete="default-strategy">
                                @if(Auth::user()->group)
                                    <option value="0">未选择</option>
                                    @foreach(Auth::user()->group->strategies as $strategy)
                                        <option value="{{ $strategy->id }}" {{ Auth::user()->configs->get(\App\Enums\UserConfigKey::DefaultStrategy) == $strategy->id ? 'selected' : '' }}>{{ $strategy->name }}</option>
                                    @endforeach
                                @else
                                    <option value="0">系统默认</option>
                                @endif
                            </x-select>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="default_album" class="block text-sm font-medium text-gray-700">默认上传相册</label>
                            <x-select id="default_album" name="configs[default_album]" autocomplete="default-album">
                                @if(Auth::user()->albums->isNotEmpty())
                                    <option value="0">未选择</option>
                                    @foreach(Auth::user()->albums as $album)
                                        <option value="{{ $album->id }}" {{ Auth::user()->configs->get(\App\Enums\UserConfigKey::DefaultAlbum) == $album->id ? 'selected' : '' }}>{{ $album->name }}</option>
                                    @endforeach
                                @else
                                    <option value="0">没有可用相册</option>
                                @endif
                            </x-select>
                        </div>

                        <div class="col-span-6">
                            <label for="password" class="block text-sm font-medium text-gray-700">密码</label>
                            <x-input type="password" name="password" id="password" placeholder="不修改请留空" autocomplete="password" />
                        </div>

                        <div class="col-span-6">
                            <fieldset>
                                <div>
                                    <legend class="text-base font-medium text-gray-900">是否自动清除预览</legend>
                                    <p class="text-sm text-gray-500">设置上传时，文件上传完成以后是否自动清除预览图片</p>
                                </div>
                                <div class="mt-4 space-x-6 flex items-center">
                                    <div class="flex items-center">
                                        <x-radio id="is_auto_clear_preview_yes" name="configs[is_auto_clear_preview]" value="1" checked="{{ Auth::user()->configs->get(\App\Enums\UserConfigKey::IsAutoClearPreview) ? 'checked' : '' }}" />
                                        <label for="is_auto_clear_preview_yes" class="ml-3 block text-sm font-medium text-gray-700">是</label>
                                    </div>
                                    <div class="flex items-center">
                                        <x-radio id="is_auto_clear_preview_no" name="configs[is_auto_clear_preview]" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" value="0" checked="{{ ! Auth::user()->configs->get(\App\Enums\UserConfigKey::IsAutoClearPreview) ? 'checked' : '' }}" />
                                        <label for="is_auto_clear_preview_no" class="ml-3 block text-sm font-medium text-gray-700">否</label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-span-6">
                            <fieldset>
                                <div>
                                    <legend class="text-base font-medium text-gray-900">图片默认权限</legend>
                                    <p class="text-sm text-gray-500">设置上传的图片默认的权限(公开还是私有，公开的图片将会出现在画廊中，你也可以通过图片管理单独设置权限)</p>
                                </div>
                                <div class="mt-4 space-x-6 flex items-center">
                                    <div class="flex items-center">
                                        <x-radio id="private" name="configs[default_permission]" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" value="{{ \App\Enums\ImagePermission::Private }}" checked="{{ Auth::user()->configs->get(\App\Enums\UserConfigKey::DefaultPermission) == \App\Enums\ImagePermission::Private ? 'checked' : '' }}" />
                                        <label for="private" class="ml-3 block text-sm font-medium text-gray-700">私有</label>
                                    </div>
                                    <div class="flex items-center">
                                        <x-radio id="public" name="configs[default_permission]" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" value="{{ \App\Enums\ImagePermission::Public }}" checked="{{ Auth::user()->configs->get(\App\Enums\UserConfigKey::DefaultPermission) == \App\Enums\ImagePermission::Public ? 'checked' : '' }}" />
                                        <label for="public" class="ml-3 block text-sm font-medium text-gray-700">公开</label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>

                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <x-button>保存设置</x-button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            $('form').submit(function (e) {
                e.preventDefault();
                axios.put(this.action, $(this).serialize()).then(response => {
                    toastr[response.data.status ? 'success' : 'warning'](response.data.message);
                });
            });
        </script>
    @endpush

</x-app-layout>
