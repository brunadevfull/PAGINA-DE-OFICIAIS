package controller;

import java.io.IOException;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class OperacaoScanearDocumento extends HttpServlet {

	private static final long serialVersionUID = 1L;

	protected void doPost(HttpServletRequest request, HttpServletResponse response)
			throws ServletException, IOException {

		// System.out.println("passou no teste");
		response.setContentType("/image/tiff");
		response.setHeader("Content-Disposition", "attachment; filename=0000000001.tif");

		/*
		 * ServletContext context = getServletContext(); String caminho =
		 * context.getInitParameter("path"); File f = new
		 * File(caminho+File.separator+"001"+File.separator+"001.tif");
		 * ServletOutputStream myOut = response.getOutputStream();
		 * 
		 * response.reset(); response.setHeader("Content-Disposition",
		 * "attachment; filename="+f.getName()); response.setContentLength((int)
		 * f.length());
		 * 
		 * System.out.println("Arquivo existe: "+f.exists()); InputStream in =
		 * new FileInputStream(f); // Transferindo bytes de entrada para sa�da
		 * byte[] buf = new byte[1024]; int len; while ((len = in.read(buf)) >
		 * 0) { myOut.write(buf, 0, len); } in.close(); myOut.flush();
		 * myOut.close();
		 */
	}
}