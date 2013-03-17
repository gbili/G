<?php
class   G_Country_Normalizer_Adapter_Objects_Usa 
extends   G_Country_Normalizer_Adapter_Objects_Abstract
{
	protected $_regex = '/u\\.?s\\.?a\\.?|e\\.?e\\.?u\\.?u\\.?|Vereinigte[- _.]Staa?ten|Estados[- _.]Un\\p{L}\\p{M}*dos.*?(?:Am\\p{L}\\p{M}*rica)?|United[- _.]states.*?(?:America)?|\\p{L}\\p{M}*tats[-_. ]unis[-_. ]d?.?Am\\p{L}\\p{M}*rique|Stati[ -_.]Uniti(?:[ -_.]d.america)?/i';
	protected $_langISO = array(G_International_LangISO::EN);
}