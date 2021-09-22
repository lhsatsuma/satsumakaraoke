<label for="{$name}">{$label}</label>
    <p>
    <label class="switch">
        <input class="{if $error}invalid-value{/if}" type="checkbox" name="checkbox_{$name}" {$value} {if $disabled}disabled="true"{/if} style="display: none"/>
        <span class="slider round slider-for-{$name}" name="checkbox_{$name}"></span>
    </label>
    <input type="hidden" name="{$name}" value="{$value_hidden}"/>
    {if $error}
        <span class='validate-error required'>{$error}</span>]
    {/if}
    </label>
    <script type="text/javascript">switchCheckbox('{$name}');</script>
</p>