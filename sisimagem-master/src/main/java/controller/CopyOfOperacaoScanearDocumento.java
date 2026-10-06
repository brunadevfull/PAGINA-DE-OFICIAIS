package controller;

import java.io.File;
import java.io.IOException;
import java.util.Iterator;
import java.util.Map;
import java.util.Set;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpSession;

import bean.Documento;

public class CopyOfOperacaoScanearDocumento extends Operacao {

	@Override
	public void executar(HttpServletRequest request) throws OperacaoException {
		HttpSession session = request.getSession();
		Documento documento = (Documento) session.getAttribute("documento");
		// pega os parametros do request
		// documento do session
		// anexo da lista
		// inserir anexo no documento

		Map m = request.getParameterMap();

		Set s = m.entrySet();
		Iterator it = s.iterator();
		documento = (Documento) request.getSession().getAttribute("documento");
		while (it.hasNext()) {

			Map.Entry<String, String[]> entry = (Map.Entry<String, String[]>) it.next();

			String key = entry.getKey();
			String[] value = entry.getValue();

			if (value.length > 1) {

				for (int i = 0; i < value.length; i++) {

					System.out.println(key + "    " + value[i]);
				}
			} else {
				Utilitaria.setDocumento(key, value[0].toString(), documento);
				System.out.println(key + " Value is " + value[0].toString());
			}
			System.out.println("-------------------");
		}

		File d = new File("c:\\trim\\temp\\");
		if (!d.exists()) {
			d.mkdir();
		}
		System.out.println(d.exists());
		File f = new File("c:\\trim\\temp\\" + Utilitaria.proximoArquivo("c:\\trim\\temp\\") + ".tif");

		try {
			f.createNewFile();
			new controller.Chooser().abrirPaint(f.getAbsolutePath());
			request.setAttribute("arquivo", f.getAbsolutePath());
			System.out.println("fechou paint");
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

		// super.setProxPagina("../incluirDocumento.jsp");
		super.setProxPagina("../views/incluirDocumento.jsp");
	}
}