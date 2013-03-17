<?php
class   G_Country_Normalizer_Adapter_Objects_Uk 
extends   G_Country_Normalizer_Adapter_Objects_Abstract
{
	protected $_regex = '/[UV]\\.?K\\.?|United Kingdom|Britain|Royaume-Uni|Re[ig]no Uni[dt]o|Vereinigtes K(?:\\p{L}\\p{M}*|oe)nigreich/i';
	protected $_langISO = array(G_International_LangISO::EN);
}