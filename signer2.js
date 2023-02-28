var fs = require("fs");
var WebCrypto = require("node-webcrypto-ossl");
var xadesjs = require("xadesjs");
var XMLSerializer = require("xmldom-alpha").XMLSerializer;
var __cripto = new WebCrypto();
xadesjs.Application.setEngine("OpenSSL", __cripto);
var datos_firma = JSON.parse(fs.readFileSync("./tmp/data.json"));
var hash = "SHA-1";
var algorithm = {
    name: "RSASSA-PKCS1-v1_5",
    modulusLength:2048,                             
    publicExponent: new Uint8Array([1, 0, 1]),      
    hash: { name: hash },
};
var signedXml = new xadesjs.SignedXml();
var xml = xadesjs.Parse(fs.readFileSync("Invoice.xml").toString());
__cripto.subtle.importKey("pkcs8", new Uint8Array(Buffer.from(datos_firma.clave_privada, "base64")).buffer, algorithm, false, ["sign"]).then(function (clave_publica) {
    console.log("Clave publica:",clave_publica);

    var x509 = [datos_firma.certificado];               

    signedXml.Sign(algorithm, clave_publica, xml, {
        x509: x509,                                     
        Keyvalue: clave_publica,                        
        references: [
            { hash: hash, transforms: ["c14n","enveloped"] }
        ],
        policy: {
            hash: hash,
            identifier: {
                //qualifier: "OIDAsURI",                //Martin
                value: "http://www.facturae.es/politica_de_firma_formato_facturae/politica_de_firma_formato_facturae_v3_1.pdf",  //"quilifier.uri",         //Martin
            },

        },
        signerRoles: {
            claimed: ["Subject"],
        },
        signingCertificate: datos_firma.certificado
    }).then(function (signature) {
        console.log(signature);
        
       
        signedXml.SignedProperties.SignedSignatureProperties.SignaturePolicyIdentifier.SignaturePolicyId.SigPolicyId.Identifier.Value = "http://www.facturae.es/politica_de_firma_formato_facturae/politica_de_firma_formato_facturae_v3_1.pdf";
        signedXml.SignedProperties.SignedSignatureProperties.SignaturePolicyIdentifier.SignaturePolicyId.SigPolicyId.Description = "Política de Firma FacturaE v3.1";
        signedXml.SignedProperties.SignedSignatureProperties.SignaturePolicyIdentifier.SignaturePolicyId.SigPolicyHash.DigestMethod.Algorithm = "http://www.w3.org/2000/09/xmldsig#sha1";
        signedXml.SignedProperties.SignedSignatureProperties.SignaturePolicyIdentifier.SignaturePolicyId.SigPolicyHash.DigestValue.Value = "Ohixl6upD6av8N7pEvDABhEL6hM=";       

        // append signature
        xml.documentElement.appendChild(signature.GetXml());
        var oSerializer = new XMLSerializer();
        var sXML = oSerializer.serializeToString(xml);
        console.log(sXML.toString());
        fs.writeFileSync("Invoice_test.xsig", sXML.toString());

        console.log(signedXml.SignedProperties.SignedSignatureProperties.SignerRole.ClaimedRoles.Count.toString());

    }, function (error) {
        console.warn(error);
    });
}, function (error) {
    console.warn(error);
});