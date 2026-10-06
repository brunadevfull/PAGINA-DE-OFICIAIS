package bean;

import java.util.LinkedList;
import java.util.List;

public class Registro {

	public static List<TipoRegistro> lTipoRegistro;

	public Registro() {
		lTipoRegistro = new LinkedList<TipoRegistro>();
		TipoRegistro tipoRegistro;

		tipoRegistro = new TipoRegistro(4, "Documentos PAPEM41");
		lTipoRegistro.add(tipoRegistro);
		tipoRegistro = new TipoRegistro(5, "Documentos PAPEM42");
		lTipoRegistro.add(tipoRegistro);
		tipoRegistro = new TipoRegistro(7, "Documento de Resposta PAPEM-41");
		lTipoRegistro.add(tipoRegistro);
		tipoRegistro = new TipoRegistro(8, "Documento de Resposta PAPEM-42");
		lTipoRegistro.add(tipoRegistro);
		tipoRegistro = new TipoRegistro(12, "Processos PAPEM42");
		lTipoRegistro.add(tipoRegistro);
		tipoRegistro = new TipoRegistro(15, "Anexos PAPEM41");
		lTipoRegistro.add(tipoRegistro);

	}
}
