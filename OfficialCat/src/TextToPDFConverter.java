import java.io.File;
import java.io.IOException;
import java.nio.charset.StandardCharsets;
import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.pdmodel.PDPage;
import org.apache.pdfbox.pdmodel.PDPageContentStream;
import org.apache.pdfbox.pdmodel.common.PDRectangle;
import org.apache.pdfbox.pdmodel.font.PDType1Font;

public class TextToPDFConverter {

    public static void main(String[] args) {
        if (args.length < 2) {
            System.out.println("Usage: TextToPDFConverter <inputFilePath1> <outputFilePath1> [<inputFilePath2> <outputFilePath2> ...]");
            System.exit(1);
        }

        for (int i = 0; i < args.length; i += 2) {
            String inputFilePath = args[i];
            String outputFilePath = args[i + 1];

            processFile(inputFilePath, outputFilePath);
        }
    }

    private static void processFile(String inputFilePath, String outputFilePath) {
        try (PDDocument document = new PDDocument()) {
            String text = readFile(inputFilePath);
            PDPage page = new PDPage();
            document.addPage(page);

            PDRectangle pageSize = page.getMediaBox();
            float margin = 50;
            float height = pageSize.getHeight() - 2 * margin;

            try (PDPageContentStream contentStream = new PDPageContentStream(document, page)) {
                writeTextToPDF(text, contentStream, margin, height);
            }

            document.save(outputFilePath);
            System.out.println("PDF file saved to " + outputFilePath);
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private static String readFile(String filePath) throws IOException {
        File file = new File(filePath);
        byte[] encoded = java.nio.file.Files.readAllBytes(file.toPath());
        return new String(encoded, StandardCharsets.UTF_8);
    }

    private static void writeTextToPDF(String text, PDPageContentStream contentStream, 
                                       float margin, float height) throws IOException {
        contentStream.setFont(PDType1Font.TIMES_ROMAN, 12);
        contentStream.setLeading(14.5f);

        float startX = margin;
        float startY = height + margin;

        String[] lines = text.split(System.lineSeparator());
        for (String line : lines) {
            if (startY < margin) {
                PDPage page = new PDPage();
                contentStream.close();
                contentStream.setFont(PDType1Font.TIMES_ROMAN, 12);
                contentStream.setLeading(14.5f);
                startY = height + margin;
            }

            contentStream.beginText();
            contentStream.newLineAtOffset(startX, startY);
            contentStream.showText(line);
            contentStream.endText();
            startY -= 14.5f;
        }
    }
}
