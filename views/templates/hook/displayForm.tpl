{*
    @package    ProductRegistrations
    @author     Sangam Kumar
    @copyright  2024 DezyNation
    @license    https://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
    @link       https://www.yourwebsite.com/
*}

{if $logged}
    {l s='Welcome, you are logged in as:'}
    {$customer_firstname} {$customer_lastname}
{else}
    {l s='Please log in to submit the form.'}
{/if}

<form id="product-registration-form" action="#" method="post" enctype="multipart/form-data">
    {foreach $fields as $field}
        <label for="{$field.name}">{$field.label}</label>
        {if $field.type == 'text' || $field.type == 'date'}
            <input type="{$field.type}" name="{$field.name}" id="{$field.name}" required />
        {elseif $field.type == 'file'}
            <input type="file" name="{$field.name}" id="{$field.name}" />
        {/if}
    {/foreach}

    <input type="submit" value="{l s='Submit'}" />
</form>
