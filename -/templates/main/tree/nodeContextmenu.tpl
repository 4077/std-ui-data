<div class="{__NODE_ID__}" instance="{__INSTANCE__}">

    <div class="inputs">
        <div class="index_form">
            <div class="input">
                <input type="text" value="{INDEX}">
            </div>
            <div class="save_button" hover="hover">save</div>
        </div>
        <div class="import_form">
            <div class="input">
                <input type="text" value="{IMPORT}" placeholder="import">
            </div>
        </div>
    </div>
    <div class="cb"></div>

    <div class="type_form">
        <div class="string button" type="string" hover="hover">string</div>
        <div class="array button" type="array" hover="hover">array</div>
        <div class="false button" type="false" hover="hover">false</div>
        <div class="true button" type="true" hover="hover">true</div>
        <div class="null button" type="null" hover="hover">null</div>
        <div class="save_button" hover="hover">save</div>
    </div>
    <div class="cb"></div>

    <div class="fn_apply_form">
        <div class="select">
            <select class="fn">
                <option value=""></option>
                <!-- fn -->
                <option value="{NAME}">{NAME}</option>
                <!-- / -->
            </select>
        </div>
        <div class="apply_button">apply</div>
        <div class="apply_to_level_button">apply to level</div>
    </div>
    <div class="cb"></div>

    <div class="sep"></div>

    <div class="copy" hover="hover">
        <div class="label">copy</div>
        <div class="button" hover="hover" action="copy/level">level</div>
        <div class="button" hover="hover" action="copy/node">node</div>
        <div class="button" hover="hover" action="copy/value">value</div>
        <div class="button" hover="hover" action="copy/index">index</div>
    </div>
    <div class="cb"></div>

    <div class="export" hover="hover">
        <div class="label">export</div>
        <div class="inputs">
            <input export="level" value="{LEVEL_JSON}">
            <input export="node" value="{NODE_JSON}">
            <input export="value" value="{VALUE_JSON}">
            <input export="index" value="{INDEX_JSON}">
        </div>
        <div class="button" hover="hover" export="level">level</div>
        <div class="button" hover="hover" export="node">node</div>
        <div class="button" hover="hover" export="value">value</div>
        <div class="button" hover="hover" export="index">index</div>
    </div>
    <div class="cb"></div>

    <div class="sep"></div>

    <div class="add" hover="hover">
        <div class="label">add</div>
        <div class="button" hover="hover" action="add/before">before</div>
        <div class="button" hover="hover" action="add/prepend">prepend</div>
        <div class="button" hover="hover" action="add/append">append</div>
        <div class="button" hover="hover" action="add/after">after</div>
    </div>
    <div class="cb"></div>

    <div class="sep"></div>

    {IGNORE_KEYS_TOGGLE_BUTTON}
    <div class="cb"></div>

    <div class="paste add" hover="hover">
        <div class="label">paste add</div>
        <div class="button" hover="hover" action="paste_aa/before">before</div>
        <div class="button" hover="hover" action="paste_aa/prepend">prepend</div>
        <div class="button" hover="hover" action="paste_aa/append">append</div>
        <div class="button" hover="hover" action="paste_aa/after">after</div>
    </div>
    <div class="cb"></div>

    <div class="paste rewrite" hover="hover">
        <div class="label">paste rewrite</div>
        <div class="button" hover="hover" action="paste_ra/before">before</div>
        <div class="button" hover="hover" action="paste_ra/prepend">prepend</div>
        <div class="button" hover="hover" action="paste_ra/append">append</div>
        <div class="button" hover="hover" action="paste_ra/after">after</div>
    </div>
    <div class="cb"></div>

    <div class="sep"></div>

    <div class="replace" hover="hover">
        <div class="label">replace</div>
        <div class="button" hover="hover" action="replace/level">level</div>
        <div class="button" hover="hover" action="replace/node">node</div>
        <div class="button" hover="hover" action="replace/value">value</div>
        <div class="button" hover="hover" action="replace/index">index</div>
    </div>
    <div class="cb"></div>

    <div class="sep"></div>

    <div class="unset" hover="hover">
        <div class="label">unset</div>
        <div class="button" hover="hover" action="unset/level">level</div>
        <div class="button" hover="hover" action="unset/node">node</div>
        <div class="button" hover="hover" action="unset/value">value</div>
    </div>
    <div class="cb"></div>

</div>
