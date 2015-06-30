<?php

class ReaderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \EDI\Reader
     */
    private $reader;

    protected function setUp()
    {
        $invoice = <<<EOF
UNA:+.? '
UNB+UNOC:3+5790000274017:14+5708601000836:14+990420:1137+17++INVOIC++++1'
UNH+30+INVOIC:D:96A:UN'
BGM+380+539602'
DTM+137:19990420:102'
RFF+CO:01671727'
NAD+BY+5708601000836::9'
RFF+VA:DK37499919'
NAD+SU++SANDVIK A/S'
RFF+VA:DK19430839'
RFF+ADE:00000767'
NAD+DP+++GRUNDFOS A/S+POUL DUE JENSENS VEJ 7+BJERRINGBRO++8850+DK'
PAI+1:14:42'
CUX+2:EUR:9'
LIN+1++V0370246:IN'
IMD+F++:::STÅLHOLDER COROMANT r 142.0-16-11'
QTY+47:5:PCE'
MOA+66:49.15:EUR'
PRI+AAA:9.83:CT::1:PCE'
RFF+CO:01671727:1'
ALC+C'
MOA+23:13.6:EUR'
LIN+2++:IN'
IMD+F++:::Packing'
QTY+47:2:PCE'
MOA+106:50:EUR'
PRI+AAA:25:CT::1:PCE'
PAC+++:::Pallet'
UNS+S'
MOA+64:100.95:EUR'
MOA+67:50.50:EUR'
MOA+86:362.00:EUR'
MOA+136:0.19:EUR'
MOA+286:25.25:EUR'
TAX+7+VAT'
MOA+124:72.36:EUR'
TAX+7+VAT'
MOA+124:539.82:DKK::9'
UNT+36+30'
UNZ+1+17'
EOF;

        $this->reader = new \EDI\Reader($invoice);
    }

    public function testGroupReading()
    {
        $groups = $this->reader->getGroups('LIN', 'UNS');
        $this->assertCount(2, $groups);

        $this->assertEquals('5', $groups[0]->readEdiDataValue('QTY', 1, 1));
    }


}
