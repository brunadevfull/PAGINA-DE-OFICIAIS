package controller;

import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;

import javax.servlet.ServletException;
import javax.servlet.ServletOutputStream;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class OperacaoAbrirDocumento extends HttpServlet {

	private static final long serialVersionUID = 1L;

	protected void doPost(HttpServletRequest request, HttpServletResponse response)
			throws ServletException, IOException {

		String nomeDocumento = ((String) request.getParameter("nomeDocumento")).trim();
		File fzip = new File(nomeDocumento);

		ServletOutputStream myOut = response.getOutputStream();
		System.out.println("Nome do documento passado por parametro: " + nomeDocumento);

		response.reset();

		/*
		 * String arquivo[] = nomeDocumento.split("\\.");
		 * if(arquivo[arquivo.length-1].equals("tif")){
		 * response.setHeader("Content-Type", "image/tiff");
		 * System.out.println("teste tiff"); }else
		 * if(arquivo[arquivo.length-1].equals("doc")){
		 * response.setHeader("Content-Type","application/msword"); }
		 */

		response.setHeader("Content-Disposition", "attachment; filename=" + fzip.getName());
		response.setContentLength((int) fzip.length());

		System.out.println("Arquivo existe? " + fzip.exists());
		InputStream in = new FileInputStream(fzip);
		// Transferindo bytes de entrada para sa�da
		byte[] buf = new byte[1024];
		int len;
		while ((len = in.read(buf)) > 0) {
			myOut.write(buf, 0, len);
		}
		in.close();
		myOut.flush();
		myOut.close();
	}
}