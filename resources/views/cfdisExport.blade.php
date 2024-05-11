<table>
    <thead>
    <tr>
        <th>Version</th>
        <th>Tipo De Comprobante</th>
        <th>Fecha Emision</th>
        <th>Serie</th>
        <th>Folio</th>
        <th>UUID</th>
        <th>RFC Emisor</th>
        <th>Nombre Emisor</th>
        <th>RFC Receptor</th>
        <th>Nombre Receptor</th>
        <th>Uso de CFDI</th>
        <th>Subtotal</th>
        <th>Descuento</th>
        <th>Retenido IEPS</th>
        <th>Retenido IVA</th>
        <th>Retenido ISR</th>
        <th>Traslado IVA 16%</th>
        <th>Total Impuestos Trasladados</th>
        <th>Total Impuestos Retenidos</th>
        {{-- <th>ISH</th> --}}
        <th>Total</th>
        <th>Moneda</th>
        <th>Tipo De Cambio</th>
        <th>Forma de pago</th>
        <th>Metodo de Pago</th>
        <th>Conceptos</th>
    </tr>
    </thead>
    <tbody>
    @foreach($cfdis as $cfdi)
        @php
            $complemento = $cfdi->getNode();
            $emisor = $complemento->searchNode('cfdi:Emisor');
            $receptor = $complemento->searchNode('cfdi:Receptor');
            $conceptos = $complemento->searchNodes('cfdi:Conceptos', 'cfdi:Concepto');
            $impuestos = $complemento->searchNode('cfdi:Impuestos');
            $tfd = $complemento->searchNode('cfdi:Complemento', 'tfd:TimbreFiscalDigital');
            $stringConceptos = '';

            $totalTrasladados = $impuestos['TotalImpuestosTrasladados'] ?? 0;
            $IVA_Traslado_16 = $impuestos['TotalTrasladosImpuestoIVA16'] ?? 0;

            $totalRetenidos = $impuestos['TotalImpuestosRetenidos'] ?? 0;
            $IVA_Retenido = $impuestos['TotalRetencionesIVA'] ?? 0;
            $ISR_Retenido = $impuestos['TotalRetencionesISR'] ?? 0;
            $IEPS_Retenido = $impuestos['TotalRetencionesIEPS'] ?? 0;

            foreach($conceptos as $concepto) {
                $stringConceptos .= $concepto['Descripcion'] . ' * ';

                // Suma de impuestos trasladados
                $nodeImpuestos = $concepto->searchNode('cfdi:Impuestos');
                if ($nodeImpuestos) {
                    foreach ($nodeImpuestos as $nodeTrasladados) {
                        if ($nodeTrasladados->searchNodes('cfdi:Traslado')) {
                            foreach($nodeTrasladados->searchNodes('cfdi:Traslado') as $nodeTraslado) {
                                if (empty($impuestos['TotalImpuestosTrasladados'])) {
                                    $totalTrasladados += (float)$nodeTraslado['Importe'];
                                }

                                if (empty($impuestos['TotalTrasladosImpuestoIVA16']) && strpos($nodeTraslado['TasaOCuota'], '0.16') !== false) {
                                    $IVA_Traslado_16 += (float)$nodeTraslado['Importe'];
                                }
                            }
                        }
                    }
                }

                // Suma de impuestos retenidos
                $nodeImpuestos = $concepto->searchNode('cfdi:Impuestos');
                if ($nodeImpuestos) {
                    foreach ($nodeImpuestos as $nodeRetenidos) {
                        if ($nodeRetenidos->searchNodes('cfdi:Retencion')) {
                            foreach($nodeRetenidos->searchNodes('cfdi:Retencion') as $nodeRetenido) {
                                if (empty($impuestos['TotalImpuestosRetenidos'])) {
                                    $totalRetenidos += (float)$nodeRetenido['Importe'];
                                }

                                if (empty($impuestos['TotalRetencionesISR']) && $nodeRetenido['Impuesto'] == '001') {
                                    $ISR_Retenido += (float)$nodeRetenido['Importe'];
                                }

                                if (empty($impuestos['TotalRetencionesIVA']) && $nodeRetenido['Impuesto'] == '002') {
                                    $IVA_Retenido += (float)$nodeRetenido['Importe'];
                                }

                                if (empty($impuestos['TotalRetencionesIEPS']) && $nodeRetenido['Impuesto'] == '003') {
                                    $IEPS_Retenido += (float)$nodeRetenido['Importe'];
                                }
                            }
                        }
                    }
                }
            }

            $tipoDeComprobante = $cfdi->getNode()['TipoDeComprobante'];

            switch ($tipoDeComprobante) {
                case 'I':
                    $tipoDeComprobante = 'Ingreso';
                    break;
                case 'E':
                    $tipoDeComprobante = 'Egreso';
                    break;
                case 'P':
                    $tipoDeComprobante = 'Pago';
                    break;
                default:
                    $tipoDeComprobante = $tipoDeComprobante;
                    break;
            }
        @endphp
        <tr>
            <td>{{ $cfdi->getNode()['Version'] }}</td>
            <td>{{ $tipoDeComprobante }}</td>
            <td>{{ $cfdi->getNode()['Fecha'] }}</td>
            <td>{{ $cfdi->getNode()['Serie'] }}</td>
            <td>{{ $cfdi->getNode()['Folio'] }}</td>
            <th>{{ $tfd['UUID'] ?? '' }}</th>
            <td>{{ $emisor['Rfc'] }}</td>
            <td>{{ $emisor['Nombre'] }}</td>
            <th>{{ $receptor['Rfc'] }}</th>
            <th>{{ $receptor['Nombre'] }}</th>
            <th>{{ $receptor['UsoCFDI'] }}</th>
            <td>{{ $cfdi->getNode()['SubTotal'] }}</td>
            <td>{{ $cfdi->getNode()['Descuento'] }}</td>
            <td>{{ $IEPS_Retenido ?? '' }}</td>
            <th>{{ $IVA_Retenido ?? '' }}</th>
            <th>{{ $ISR_Retenido ?? '' }}</th>
            <td>{{ $IVA_Traslado_16 ?? '' }}</td>
            <th>{{ $totalTrasladados ?? '' }}</th>
            <th>{{ $totalRetenidos ?? '' }}</th>
            {{-- <th>ISH</th> --}}
            <td>{{ $cfdi->getNode()['Total'] }}</td>
            <td>{{ $cfdi->getNode()['Moneda'] }}</td>
            <td>{{ $cfdi->getNode()['TipoCambio'] }}</td>
            <td>{{ $cfdi->getNode()['FormaPago'] }}</td>
            <td>{{ $cfdi->getNode()['MetodoPago'] }}</td>
            <th>{{ $stringConceptos }}</th>

        </tr>
    @endforeach
    </tbody>
</table>
